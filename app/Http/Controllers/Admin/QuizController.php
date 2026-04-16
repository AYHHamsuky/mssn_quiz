<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['questions', 'participations'])->latest()->paginate(10);
        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('admin.quizzes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:general,debate,impromptu_speech,essay,arabic_passage,english_passage',
            'subject' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'question_timer' => 'required|integer|min:10|max:300',
            'points_per_question' => 'required|integer|min:1',
        ]);

        // Set default start and end times
        $validated['start_time'] = now();
        $validated['end_time'] = now()->addYear();

        $quiz = Quiz::create($validated);

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully!');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['questions', 'participations.school']);
        return view('admin.quizzes.show', compact('quiz'));
    }

    public function liveControl(Quiz $quiz)
    {
        if (!$quiz->is_live) {
            return redirect()->route('admin.quizzes.show', $quiz)
                ->with('error', 'This quiz is not live. Start the quiz first.');
        }

        $quiz->load(['questions', 'participations.school', 'participations.answers', 'currentQuestion']);
        return view('admin.quizzes.live-control', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        return view('admin.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:general,debate,impromptu_speech,essay,arabic_passage,english_passage',
            'subject' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'question_timer' => 'required|integer|min:10|max:300',
            'points_per_question' => 'required|integer|min:1',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Quiz updated successfully!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully!');
    }

    public function addQuestion(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
            'correct_answer' => 'nullable|string',
            'passage_text' => 'nullable|string',
            'question_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
            'order' => 'nullable|integer',
        ]);

        $audioPath = null;
        if ($request->hasFile('question_audio')) {
            $audioPath = $request->file('question_audio')->store('question_audios', 'public');
        }

        $question = $quiz->questions()->create([
            'question_text' => $validated['question_text'],
            'options' => $validated['options'] ?? null,
            'correct_answer' => $validated['correct_answer'] ?? null,
            'passage_text' => $validated['passage_text'] ?? null,
            'question_audio_path' => $audioPath,
            'order' => $validated['order'] ?? $quiz->questions()->count() + 1,
        ]);

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Question added successfully!');
    }

    public function deleteQuestion(Quiz $quiz, Question $question)
    {
        if ($question->question_audio_path) {
            Storage::disk('public')->delete($question->question_audio_path);
        }
        
        $question->delete();

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Question deleted successfully!');
    }

    public function toggleLive(Quiz $quiz)
    {
        $isStarting = !$quiz->is_live;
        
        if ($isStarting) {
            // Reset completion status when restarting quiz
            $quiz->update([
                'is_live' => true,
                'completed_at' => null,
                'current_question_id' => null,
                'question_started_at' => null,
                'show_answer' => false,
            ]);
            
            // Reset all participations if quiz was completed
            $quiz->participations()->update([
                'status' => 'registered',
                'total_score' => 0,
                'correct_answers' => 0,
                'wrong_answers' => 0,
                'completed_at' => null,
            ]);
            
            // Delete previous answers
            foreach ($quiz->participations as $participation) {
                $participation->answers()->delete();
            }
        } else {
            $quiz->update(['is_live' => false]);
        }

        $status = $isStarting ? 'started' : 'stopped';
        return redirect()->back()
            ->with('success', "Quiz {$status} successfully!");
    }

    public function uploadQuestions(Request $request, Quiz $quiz)
    {
        $request->validate([
            'questions_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048',
        ]);

        $file = $request->file('questions_file');
        $extension = $file->getClientOriginalExtension();
        
        try {
            if (in_array($extension, ['csv', 'txt'])) {
                $this->importFromCsv($file, $quiz);
            } else {
                return redirect()->back()->with('error', 'Please use CSV format for now. Excel support coming soon.');
            }

            return redirect()->route('admin.quizzes.show', $quiz)
                ->with('success', 'Questions uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error uploading questions: ' . $e->getMessage());
        }
    }

    private function importFromCsv($file, $quiz)
    {
        $content = file_get_contents($file->getRealPath());
        $order = $quiz->questions()->count() + 1;

        // Split by numbered questions (1. 2. 3. etc.)
        $questions = preg_split('/\n\s*\d+\.\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($questions as $questionBlock) {
            $questionBlock = trim($questionBlock);
            if (empty($questionBlock)) continue;

            // Split into lines
            $lines = array_filter(array_map('trim', explode("\n", $questionBlock)));
            if (count($lines) < 2) continue; // Need at least question and one option

            // First line(s) until we hit 'a.' is the question text
            $questionText = '';
            $optionLines = [];
            $foundOptions = false;

            foreach ($lines as $line) {
                if (preg_match('/^[a-d][\.\)]\s+/', $line)) {
                    $foundOptions = true;
                    $optionLines[] = $line;
                } elseif (!$foundOptions) {
                    $questionText .= ($questionText ? ' ' : '') . $line;
                }
            }

            if (empty($questionText)) continue;

            // Process options and find correct answer
            $options = [];
            $correctAnswer = '';

            foreach ($optionLines as $optionLine) {
                // Check if this option is marked as correct (has *)
                $isCorrect = (strpos($optionLine, '*') !== false);
                
                // Clean the option text (remove * if present)
                $cleanOption = str_replace('*', '', $optionLine);
                $cleanOption = trim($cleanOption);
                
                $options[] = $cleanOption;

                if ($isCorrect) {
                    // Extract just the letter for correct answer
                    if (preg_match('/^([a-d])[\.\)]/', $cleanOption, $matches)) {
                        $correctAnswer = strtoupper($matches[1]);
                    }
                }
            }

            // Create the question
            $questionData = [
                'quiz_id' => $quiz->id,
                'question_text' => $questionText,
                'order' => $order++,
                'is_active' => true,
            ];

            if (!empty($options)) {
                $questionData['options'] = $options;
            }

            if (!empty($correctAnswer)) {
                $questionData['correct_answer'] = $correctAnswer;
            }

            Question::create($questionData);
        }
    }

    public function downloadTemplate()
    {
        $txtContent = "1. What is 2 + 2?
a. 3
b. 4*
c. 5
d. 6

2. What is the capital of France?
a. London
b. Paris*
c. Berlin
d. Rome

3. A hunter 1.6 m tall views a bird on top of a tree at an angle of 45°. If the distance between the hunter and the tree is 10.4 m, find the height of the tree
a. 9.0 m
b. 12.0 m*
c. 8.8 m
d. 10.4 m

4. Find the mean of the data: 7, -3, 4, -2, 5, -9, 4, 8, -6, and 12
a. 3
b. 4
c. 1
d. 2*

5. If the 9th term of an A.P. is five times the 5th term, find the relationship between a and d
a. 2a + d = 0
b. 3a + 5d = 0
c. a + 3d = 0*
d. a + 2d = 0
";

        return response($txtContent)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="quiz_questions_template.txt"');
    }

    public function setCurrentQuestion(Request $request, Quiz $quiz, Question $question)
    {
        // Verify the question belongs to this quiz
        if ($question->quiz_id !== $quiz->id) {
            return redirect()->back()->with('error', 'Question does not belong to this quiz');
        }

        $quiz->update([
            'current_question_id' => $question->id,
            'question_started_at' => now(),
            'timer_started_at' => null, // Timer will start after question is read twice
            'show_answer' => false, // Hide answer when setting new question
        ]);

        return redirect()->back()->with('success', 'Question is now live for all schools!');
    }

    public function nextQuestion(Quiz $quiz)
    {
        $currentQuestion = $quiz->currentQuestion;
        
        if (!$currentQuestion) {
            // Start with first question
            $nextQuestion = $quiz->questions()->orderBy('order')->first();
        } else {
            // Get next question by order
            $nextQuestion = $quiz->questions()
                ->where('order', '>', $currentQuestion->order)
                ->orderBy('order')
                ->first();
        }

        if ($nextQuestion) {
            $quiz->update([
                'current_question_id' => $nextQuestion->id,
                'question_started_at' => now(),
                'timer_started_at' => null, // Timer will start after question is read twice
                'show_answer' => false, // Hide answer when moving to new question
            ]);
            return redirect()->back()->with('success', 'Moved to next question!');
        }

        return redirect()->back()->with('info', 'No more questions available.');
    }

    public function previousQuestion(Quiz $quiz)
    {
        $currentQuestion = $quiz->currentQuestion;
        
        if (!$currentQuestion) {
            return redirect()->back()->with('error', 'No current question set');
        }

        $previousQuestion = $quiz->questions()
            ->where('order', '<', $currentQuestion->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousQuestion) {
            $quiz->update([
                'current_question_id' => $previousQuestion->id,
                'question_started_at' => now(),
                'timer_started_at' => null, // Timer will start after question is read twice
                'show_answer' => false, // Hide answer when moving to previous question
            ]);
            return redirect()->back()->with('success', 'Moved to previous question!');
        }

        return redirect()->back()->with('info', 'Already at first question.');
    }

    public function clearCurrentQuestion(Quiz $quiz)
    {
        $quiz->update([
            'current_question_id' => null,
            'timer_started_at' => null,
            'question_started_at' => null,
            'show_answer' => false,
        ]);

        return redirect()->back()->with('success', 'Current question cleared! Quiz is paused.');
    }

    public function completeQuiz(Quiz $quiz)
    {
        $quiz->update([
            'current_question_id' => null,
            'question_started_at' => null,
            'timer_started_at' => null,
            'show_answer' => false,
            'completed_at' => now(),
            'is_live' => false,
        ]);

        // Update all participations to completed
        $quiz->participations()->where('status', 'in_progress')->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Quiz has been completed successfully! All schools have been marked as finished.');
    }

    public function startTimer(Quiz $quiz)
    {
        if (!$quiz->current_question_id) {
            return response()->json(['error' => 'No active question'], 400);
        }

        $quiz->update(['timer_started_at' => now()]);

        return response()->json(['success' => true, 'timer_started_at' => $quiz->timer_started_at]);
    }

    public function revealAnswer(Quiz $quiz)
    {
        if (!$quiz->current_question_id) {
            return redirect()->back()->with('error', 'No active question to reveal answer for.');
        }

        $quiz->update(['show_answer' => true]);

        return redirect()->back()->with('success', 'Answer revealed to all schools!');
    }

    public function hideAnswer(Quiz $quiz)
    {
        $quiz->update(['show_answer' => false]);

        return redirect()->back()->with('success', 'Answer hidden!');
    }
}
