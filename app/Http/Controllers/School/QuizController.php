<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizParticipation;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index()
    {
        $liveQuizzes = Quiz::where('is_live', true)
            ->where('is_active', true)
            ->with(['currentQuestion', 'participations' => function($query) {
                $query->where('school_id', Auth::user()->school_id);
            }])
            ->latest()
            ->get();

        $upcomingQuizzes = Quiz::where('is_active', true)
            ->where('is_live', false)
            ->latest('start_time')
            ->get();

        return view('school.quizzes.index', compact('liveQuizzes', 'upcomingQuizzes'));
    }

    public function register(Quiz $quiz)
    {
        $school = Auth::user()->school;

        if ($quiz->participations()->where('school_id', $school->id)->exists()) {
            return redirect()->back()->with('error', 'Already registered for this quiz!');
        }

        QuizParticipation::create([
            'school_id' => $school->id,
            'quiz_id' => $quiz->id,
            'status' => 'registered',
        ]);

        return redirect()->back()->with('success', 'Successfully registered for the quiz!');
    }

    public function participate(Quiz $quiz)
    {
        $school = Auth::user()->school;
        
        if (!$quiz->is_live) {
            return redirect()->route('school.quizzes.index')
                ->with('error', 'This quiz is not currently live!');
        }

        // Check if quiz has a current question set
        if (!$quiz->current_question_id) {
            return redirect()->route('school.quizzes.index')
                ->with('info', 'Waiting for admin to start the quiz questions...');
        }

        $participation = QuizParticipation::firstOrCreate(
            [
                'school_id' => $school->id,
                'quiz_id' => $quiz->id,
            ],
            [
                'status' => 'registered',
            ]
        );

        if ($participation->status === 'registered') {
            $participation->start();
        }

        // Load the current question set by admin
        $quiz->load(['currentQuestion', 'questions']);
        $currentQuestion = $quiz->currentQuestion;

        // Check if this question was already answered
        $alreadyAnswered = Answer::where('quiz_participation_id', $participation->id)
            ->where('question_id', $currentQuestion->id)
            ->exists();

        return view('school.quizzes.participate', compact('quiz', 'participation', 'currentQuestion', 'alreadyAnswered'));
    }

    public function submitAnswer(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string',
            'time_taken' => 'nullable|integer',
        ]);

        $school = Auth::user()->school;
        $participation = QuizParticipation::where('school_id', $school->id)
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        $question = Question::findOrFail($validated['question_id']);

        // Verify the question belongs to this quiz
        if ($question->quiz_id !== $quiz->id) {
            return response()->json(['error' => 'Question does not belong to this quiz'], 403);
        }
        
        // Check if already answered
        if ($participation->answers()->where('question_id', $question->id)->exists()) {
            return response()->json(['error' => 'Already answered this question'], 400);
        }

        $isCorrect = $question->isCorrectAnswer($validated['answer']);
        $pointsEarned = $isCorrect ? $quiz->points_per_question : 0;

        // Save answer
        Answer::create([
            'quiz_participation_id' => $participation->id,
            'question_id' => $question->id,
            'answer_text' => $validated['answer'],
            'is_correct' => $isCorrect,
            'points_earned' => $pointsEarned,
            'time_taken' => $validated['time_taken'] ?? null,
        ]);

        // Update participation
        if ($isCorrect) {
            $participation->addPoints($pointsEarned);
        } else {
            $participation->recordWrongAnswer();
        }

        // Check if quiz is complete
        $totalQuestions = $quiz->questions()->count();
        $answeredQuestions = $participation->answers()->count();

        if ($answeredQuestions >= $totalQuestions) {
            $participation->complete();
            return response()->json([
                'success' => true,
                'completed' => true,
                'message' => 'Quiz completed!',
                'total_score' => $participation->total_score,
            ]);
        }

        // Get next question
        $nextQuestion = $this->getCurrentQuestion($participation);

        $participation->refresh();

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'points_earned' => $pointsEarned,
            'total_score' => $participation->total_score,
            'next_question' => $nextQuestion,
        ]);
    }

    private function getCurrentQuestion($participation)
    {
        $answeredQuestionIds = $participation->answers()->pluck('question_id')->toArray();
        
        return $participation->quiz->questions()
            ->whereNotIn('id', $answeredQuestionIds)
            ->orderBy('order')
            ->first();
    }

    public function results(Quiz $quiz)
    {
        $school = Auth::user()->school;
        
        $participation = QuizParticipation::where('school_id', $school->id)
            ->where('quiz_id', $quiz->id)
            ->with(['answers.question', 'quiz'])
            ->firstOrFail();

        $leaderboard = QuizParticipation::where('quiz_id', $quiz->id)
            ->with('school')
            ->orderBy('total_score', 'desc')
            ->get();

        $rank = $leaderboard->search(function($item) use ($participation) {
            return $item->id === $participation->id;
        }) + 1;

        return view('school.quizzes.results', compact('participation', 'leaderboard', 'rank'));
    }

    public function checkQuizState(Quiz $quiz)
    {
        return response()->json([
            'is_live' => $quiz->is_live,
            'current_question_id' => $quiz->current_question_id,
            'timer_started_at' => $quiz->timer_started_at ? $quiz->timer_started_at->toIso8601String() : null,
            'show_answer' => $quiz->show_answer,
            'completed_at' => $quiz->completed_at ? $quiz->completed_at->toIso8601String() : null,
        ]);
    }
}
