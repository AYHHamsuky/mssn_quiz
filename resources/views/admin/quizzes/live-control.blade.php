@extends('layouts.app')

@section('title', 'Live Quiz Control - ' . $quiz->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🔴 Live Quiz Control</h1>
            <p class="text-gray-600 mt-1">{{ $quiz->title }} - {{ $quiz->subject }}</p>
        </div>
        <div class="flex space-x-3">
            <form action="{{ route('admin.quizzes.complete', $quiz) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to STOP and COMPLETE this quiz? This will end the quiz for all schools and mark it as completed.')">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md font-semibold flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                    </svg>
                    Stop & Complete Quiz
                </button>
            </form>
            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md">
                Back to Quiz Details
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Total Questions</p>
            <p class="text-2xl font-bold text-gray-900">{{ $quiz->questions->count() }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Active Participants</p>
            <p class="text-2xl font-bold text-green-600">{{ $quiz->participations->count() }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Current Question</p>
            <p class="text-2xl font-bold text-indigo-600">
                @if($quiz->current_question_id)
                    #{{ $quiz->questions->search(fn($q) => $q->id === $quiz->current_question_id) + 1 }}
                @else
                    Not Started
                @endif
            </p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Status</p>
            <p class="text-lg font-semibold">
                <span class="text-green-600 flex items-center">
                    <span class="animate-pulse mr-2">🔴</span> Live
                </span>
            </p>
        </div>
    </div>

    <!-- Question Controls -->
    @if($quiz->questions->count() > 0)
    <div class="bg-linear-to-r from-green-50 to-blue-50 border-2 border-green-300 shadow-lg rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="h-6 w-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                Question Control Panel
            </h2>
            @if($quiz->currentQuestion)
            <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm font-semibold animate-pulse">
                Question #{{ $quiz->questions->search(fn($q) => $q->id === $quiz->current_question_id) + 1 }} is LIVE
            </span>
            @else
            <span class="px-3 py-1 bg-yellow-500 text-white rounded-full text-sm font-semibold">
                No question active - Click "Next Question" to start
            </span>
            @endif
        </div>

        @if($quiz->currentQuestion)
        <!-- Timer Display for Admin -->
        <div class="bg-linear-to-r from-red-50 to-orange-50 rounded-lg p-6 mb-4 border-2 border-red-300">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-800">Time Remaining:</span>
                <span id="admin-timer" class="text-4xl font-bold text-red-600">{{ $quiz->question_timer }}</span>
            </div>
            <div class="mt-3 w-full bg-gray-200 rounded-full h-4">
                <div id="admin-timer-bar" class="bg-red-600 h-4 rounded-full transition-all duration-1000" style="width: 100%"></div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 mb-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-600 mb-2">Currently Displaying to All Schools:</p>
            <p class="text-lg font-semibold text-gray-900">{{ $quiz->currentQuestion->question_text }}</p>
            @if($quiz->currentQuestion->options)
            <div class="mt-2 grid grid-cols-2 gap-2">
                @foreach(explode("\n", implode("\n", $quiz->currentQuestion->options)) as $option)
                    @if(trim($option))
                    <p class="text-sm text-gray-600">{{ trim($option) }}</p>
                    @endif
                @endforeach
            </div>
            @endif
            @if($quiz->currentQuestion->correct_answer)
            <div class="mt-3 p-3 {{ $quiz->show_answer ? 'bg-green-100 border-2 border-green-500' : 'bg-gray-100' }} rounded">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium {{ $quiz->show_answer ? 'text-green-800' : 'text-gray-700' }}">
                        {{ $quiz->show_answer ? '✅ ' : '🔒 ' }}Correct Answer: 
                        @if($quiz->show_answer)
                            <span class="font-bold">{{ $quiz->currentQuestion->correct_answer }}</span>
                        @else
                            <span class="font-bold text-gray-400">●●●●●●</span>
                        @endif
                    </p>
                    @if($quiz->show_answer)
                    <span class="px-2 py-1 bg-green-600 text-white text-xs rounded-full font-semibold">
                        REVEALED
                    </span>
                    @else
                    <span class="px-2 py-1 bg-gray-400 text-white text-xs rounded-full font-semibold">
                        HIDDEN
                    </span>
                    @endif
                </div>
            </div>
            @endif
            <p class="text-xs text-gray-500 mt-2">Started: {{ $quiz->question_started_at->diffForHumans() }}</p>
        </div>
        @endif

        <div class="flex items-center justify-center space-x-4 flex-wrap gap-2">
            <form action="{{ route('admin.quizzes.previous-question', $quiz) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center font-medium"
                        {{ !$quiz->currentQuestion || $quiz->questions->first()->id === $quiz->current_question_id ? 'disabled' : '' }}>
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous Question
                </button>
            </form>

            @if($quiz->currentQuestion)
                @if($quiz->show_answer)
                <form action="{{ route('admin.quizzes.hide-answer', $quiz) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg flex items-center font-medium">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                        Hide Answer
                    </button>
                </form>
                @else
                <form action="{{ route('admin.quizzes.reveal-answer', $quiz) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center font-medium shadow-lg">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Reveal Answer
                    </button>
                </form>
                @endif
            @endif

            <form action="{{ route('admin.quizzes.next-question', $quiz) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg flex items-center font-bold text-lg shadow-lg">
                    @if(!$quiz->currentQuestion)
                        ▶️ Start First Question
                    @else
                        Next Question ➡️
                    @endif
                </button>
            </form>

            @if($quiz->currentQuestion)
            <form action="{{ route('admin.quizzes.clear-question', $quiz) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium">
                    ⏸️ Pause (Clear Question)
                </button>
            </form>
            @endif
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    No questions added yet. Please add questions to this quiz before starting.
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="font-medium underline">Go to quiz details</a>
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Questions List with Quick Access -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">All Questions - Quick Selection</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($quiz->questions as $index => $question)
            <div class="p-4 {{ $quiz->current_question_id === $question->id ? 'bg-green-50 border-l-4 border-green-500' : 'hover:bg-gray-50' }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $quiz->current_question_id === $question->id ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }} font-bold mr-3">
                                {{ $index + 1 }}
                            </span>
                            <h3 class="text-base font-medium text-gray-900">{{ Str::limit($question->question_text, 100) }}</h3>
                            @if($quiz->current_question_id === $question->id)
                            <span class="ml-3 px-2 py-1 bg-green-500 text-white text-xs rounded-full font-semibold animate-pulse">LIVE NOW</span>
                            @endif
                        </div>
                        @if($question->correct_answer)
                        <p class="ml-11 text-xs text-gray-400 font-medium">🔒 Answer: ●●●●●●●●</p>
                        @endif
                    </div>
                    <form action="{{ route('admin.quizzes.set-current-question', [$quiz, $question]) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="ml-4 px-4 py-2 rounded-md text-sm font-medium {{ $quiz->current_question_id === $question->id ? 'bg-green-100 text-green-700 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 text-white' }}"
                                {{ $quiz->current_question_id === $question->id ? 'disabled' : '' }}>
                            {{ $quiz->current_question_id === $question->id ? '✓ Active' : 'Set as Current' }}
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                No questions added yet.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Live Participants -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Active Participants ({{ $quiz->participations->count() }})</h2>
        </div>
        <div class="p-6">
            @if($quiz->participations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($quiz->participations as $participation)
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900">{{ $participation->school->name }}</h3>
                    <div class="mt-2 text-sm text-gray-600">
                        <p>Score: <span class="font-bold text-indigo-600">{{ $participation->total_score }} pts</span></p>
                        <p>Correct: {{ $participation->correct_answers }} / {{ $participation->answers->count() }}</p>
                        <p class="text-xs mt-1">
                            Status: 
                            <span class="px-2 py-1 rounded {{ $participation->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($participation->status) }}
                            </span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center text-gray-500">No participants yet</p>
            @endif
        </div>
    </div>
</div>

<script>
// Admin Timer with Voice Announcements and Synchronized Timing
let adminTimeLeft = {{ $quiz->question_timer }};
let adminTimerInterval;
let voiceAnnounced = {
    '20': false,
    '10': false,
    '5': false,
    '3': false,
    '2': false,
    '1': false
};
let questionReadCount = 0;
let timerStarted = {{ $quiz->timer_started_at ? 'true' : 'false' }};

function speak(text, rate = 0.8) {
    return new Promise((resolve) => {
        if ('speechSynthesis' in window) {
            speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.rate = rate; // Slower rate for clarity
            utterance.pitch = 1.0;
            utterance.volume = 1.0;
            utterance.onend = resolve;
            speechSynthesis.speak(utterance);
        } else {
            resolve();
        }
    });
}

function startAdminTimer() {
    const timerElement = document.getElementById('admin-timer');
    const timerBar = document.getElementById('admin-timer-bar');
    const totalTime = {{ $quiz->question_timer }};

    adminTimerInterval = setInterval(() => {
        adminTimeLeft--;
        timerElement.textContent = adminTimeLeft;
        
        const percentage = (adminTimeLeft / totalTime) * 100;
        timerBar.style.width = percentage + '%';

        // Voice announcements at specific intervals
        if (adminTimeLeft === 20 && !voiceAnnounced['20']) {
            speak('20 seconds remaining', 1.0);
            voiceAnnounced['20'] = true;
        } else if (adminTimeLeft === 10 && !voiceAnnounced['10']) {
            speak('10 seconds remaining', 1.0);
            voiceAnnounced['10'] = true;
        } else if (adminTimeLeft === 5 && !voiceAnnounced['5']) {
            speak('5 seconds', 1.0);
            voiceAnnounced['5'] = true;
        } else if (adminTimeLeft === 3 && !voiceAnnounced['3']) {
            speak('3', 1.0);
            voiceAnnounced['3'] = true;
        } else if (adminTimeLeft === 2 && !voiceAnnounced['2']) {
            speak('2', 1.0);
            voiceAnnounced['2'] = true;
        } else if (adminTimeLeft === 1 && !voiceAnnounced['1']) {
            speak('1', 1.0);
            voiceAnnounced['1'] = true;
        } else if (adminTimeLeft === 0) {
            speak('Time is up', 1.0);
            clearInterval(adminTimerInterval);
        }

        if (adminTimeLeft <= 0) {
            clearInterval(adminTimerInterval);
        }
    }, 1000);
}

async function readQuestionTwiceAndStartTimer() {
    const questionText = '{{ addslashes($quiz->currentQuestion->question_text ?? '') }}';
    
    if (questionText && 'speechSynthesis' in window) {
        // Read question first time (slowly)
        await speak(questionText, 0.7);
        await new Promise(resolve => setTimeout(resolve, 1000)); // 1 second pause
        
        // Read question second time (slowly)
        await speak(questionText, 0.7);
        await new Promise(resolve => setTimeout(resolve, 1000)); // 1 second pause
        
        // Now start the timer for everyone
        if (!timerStarted) {
            // Set timer_started_at on server
            fetch('{{ route('admin.quizzes.start-timer', $quiz) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                timerStarted = true;
                startAdminTimer();
            });
        }
    } else {
        // No speech available, start timer immediately
        if (!timerStarted) {
            startAdminTimer();
        }
    }
}

@if($quiz->currentQuestion)
@if($quiz->timer_started_at)
// Timer already started, calculate remaining time
const timerStartedAt = new Date('{{ $quiz->timer_started_at->toIso8601String() }}');
const now = new Date();
const elapsed = Math.floor((now - timerStartedAt) / 1000);
adminTimeLeft = Math.max(0, {{ $quiz->question_timer }} - elapsed);

if (adminTimeLeft > 0) {
    startAdminTimer();
}
@else
// Timer not started yet, read question twice then start
readQuestionTwiceAndStartTimer();
@endif
@endif

// Auto-refresh page every 30 seconds to stay in sync
setInterval(() => {
    // Only refresh if not in the middle of a question
    if (adminTimeLeft <= 0 || adminTimeLeft > {{ $quiz->question_timer - 5 }}) {
        // Don't refresh, admin controls manually
    }
}, 30000);
</script>
@endsection
