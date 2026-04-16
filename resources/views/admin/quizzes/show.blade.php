@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $quiz->title }}</h1>
            <p class="text-gray-600 mt-1">{{ $quiz->getTypeLabel() }} @if($quiz->subject)- {{ $quiz->subject }}@endif</p>
        </div>
        <div class="flex space-x-3">
            @if($quiz->is_live)
                <a href="{{ route('admin.quizzes.live-control', $quiz) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md flex items-center">
                    <span class="animate-pulse mr-2">🔴</span> Go to Live Control
                </a>
                <form action="{{ route('admin.quizzes.toggle-live', $quiz) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md">
                        Stop Quiz
                    </button>
                </form>
            @else
                <form action="{{ route('admin.quizzes.toggle-live', $quiz) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md">
                        {{ $quiz->completed_at ? 'Restart Quiz' : 'Start Quiz Live' }}
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                Edit Quiz
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Questions</p>
            <p class="text-2xl font-bold text-gray-900">{{ $quiz->questions->count() }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Participants</p>
            <p class="text-2xl font-bold text-gray-900">{{ $quiz->participations->count() }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Duration</p>
            <p class="text-2xl font-bold text-gray-900">{{ $quiz->duration_minutes }} min</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Status</p>
            <p class="text-lg font-semibold">
                @if($quiz->completed_at)
                    <span class="text-gray-600">✅ Completed</span>
                @elseif($quiz->is_live)
                    <span class="text-green-600">🔴 Live</span>
                @elseif($quiz->hasEnded())
                    <span class="text-gray-600">Ended</span>
                @else
                    <span class="text-yellow-600">Ready</span>
                @endif
            </p>
            @if($quiz->completed_at)
            <p class="text-xs text-gray-500 mt-1">{{ $quiz->completed_at->format('M d, Y H:i') }}</p>
            @endif
        </div>
    </div>

    @if($quiz->is_live)
    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">
                    This quiz is currently <strong>LIVE</strong>! 
                    <a href="{{ route('admin.quizzes.live-control', $quiz) }}" class="font-medium underline">
                        Go to Live Control Panel to manage questions →
                    </a>
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Add Question Form -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Add Questions</h2>
        
        <!-- Bulk Upload Section -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-lg font-medium text-blue-900 mb-3">📁 Bulk Upload Questions</h3>
            <form action="{{ route('admin.quizzes.upload-questions', $quiz) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Text File with Questions</label>
                    <input type="file" name="questions_file" accept=".csv,.txt" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-600 mt-1">Upload a .txt or .csv file with your questions. Maximum file size: 2MB</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Upload Questions
                    </button>
                    <a href="{{ route('admin.quizzes.download-template') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium underline">
                        📥 Download Sample Template
                    </a>
                </div>
                <div class="mt-2 text-xs text-gray-600 bg-gray-50 p-3 rounded">
                    <p class="font-semibold mb-2">📝 Format Instructions:</p>
                    <div class="font-mono text-xs bg-white p-2 rounded border">
                        <p class="text-blue-600">1. What is 2 + 2?</p>
                        <p>a. 3</p>
                        <p>b. 4*</p>
                        <p>c. 5</p>
                        <p>d. 6</p>
                        <p class="mt-2 text-blue-600">2. What is the capital of France?</p>
                        <p>a. London</p>
                        <p>b. Paris*</p>
                        <p>c. Berlin</p>
                        <p>d. Rome</p>
                    </div>
                    <ul class="list-disc list-inside space-y-1 mt-2">
                        <li>Start each question with a number followed by a period (1., 2., 3.)</li>
                        <li>Put each option on a new line starting with a letter (a., b., c., d.)</li>
                        <li>Mark the correct answer with an asterisk (*) after the option</li>
                        <li>Leave a blank line between questions</li>
                    </ul>
                </div>
            </form>
        </div>

        <hr class="my-6">

        <!-- Single Question Form -->
        <h3 class="text-lg font-medium mb-3">➕ Add Single Question</h3>
        <form action="{{ route('admin.quizzes.questions.add', $quiz) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Question Text</label>
                    <textarea name="question_text" rows="3" required
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                @if(in_array($quiz->type, ['general']))
                <div>
                    <label class="block text-sm font-medium text-gray-700">Options (one per line)</label>
                    <textarea name="options[]" rows="4" placeholder="A. Option 1&#10;B. Option 2&#10;C. Option 3&#10;D. Option 4"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Enter each option on a new line</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Correct Answer</label>
                    <input type="text" name="correct_answer" placeholder="e.g., A or Option 1"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                @endif

                @if(in_array($quiz->type, ['arabic_passage', 'english_passage']))
                <div>
                    <label class="block text-sm font-medium text-gray-700">Passage Text</label>
                    <textarea name="passage_text" rows="5"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700">Audio File (Optional)</label>
                    <input type="file" name="question_audio" accept=".mp3,.wav,.ogg"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    Add Question
                </button>
            </div>
        </form>
    </div>

    <!-- Questions List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Questions ({{ $quiz->questions->count() }})</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($quiz->questions as $index => $question)
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <h3 class="text-lg font-medium text-gray-900">Question {{ $index + 1 }}</h3>
                        </div>
                        <p class="text-gray-700 mt-2">{{ $question->question_text }}</p>
                        
                        @if($question->options)
                        <div class="mt-3 space-y-1">
                            @foreach(explode("\n", implode("\n", $question->options)) as $option)
                                @if(trim($option))
                                <p class="text-sm text-gray-600">{{ $option }}</p>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        @if($question->correct_answer)
                        <p class="mt-2 text-sm text-gray-400 font-medium">🔒 Correct Answer: ●●●●●●●● (Hidden until revealed)</p>
                        @endif

                        @if($question->passage_text)
                        <div class="mt-3 p-3 bg-gray-50 rounded">
                            <p class="text-sm text-gray-700">{{ Str::limit($question->passage_text, 200) }}</p>
                        </div>
                        @endif

                        @if($question->question_audio_path)
                        <audio controls class="mt-3">
                            <source src="{{ Storage::url($question->question_audio_path) }}" type="audio/mpeg">
                        </audio>
                        @endif
                    </div>
                    <form action="{{ route('admin.quizzes.questions.delete', [$quiz, $question]) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 ml-4">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                No questions added yet. Add your first question above.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
