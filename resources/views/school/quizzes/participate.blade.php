@extends('layouts.app')

@section('title', 'Participate in Quiz')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ $quiz->title }}</h1>
            <p class="text-gray-600">{{ $quiz->getTypeLabel() }}</p>
        </div>

        @if($currentQuestion)
        <div id="quiz-container">
            <!-- Timer -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Time Remaining:</span>
                    <span id="timer" class="text-2xl font-bold text-red-600">{{ $quiz->question_timer }}</span>
                </div>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                    <div id="timer-bar" class="bg-red-600 h-2.5 rounded-full transition-all duration-1000" style="width: 100%"></div>
                </div>
            </div>

            <!-- Already Answered Notice -->
            @if($alreadyAnswered)
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="text-blue-800 font-semibold">You've Already Answered This Question</h3>
                        <p class="text-blue-700 text-sm">Waiting for the next question from the admin...</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Question -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Current Question</h2>
                
                <div class="p-6 bg-gray-50 rounded-lg">
                    <p class="text-lg text-gray-900" id="question-text">{{ $currentQuestion->question_text }}</p>
                    
                    @if($currentQuestion->question_audio_path)
                    <div class="mt-4">
                        <audio id="question-audio" controls autoplay class="w-full">
                            <source src="{{ Storage::url($currentQuestion->question_audio_path) }}" type="audio/mpeg">
                        </audio>
                    </div>
                    @endif

                    @if($currentQuestion->passage_text)
                    <div class="mt-4 p-4 bg-white rounded border border-gray-200">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $currentQuestion->passage_text }}</p>
                    </div>
                    @endif

                    @if($currentQuestion->options)
                    <div class="mt-6 space-y-3" id="options-container">
                        @foreach(explode("\n", implode("\n", $currentQuestion->options)) as $index => $option)
                            @if(trim($option))
                            <button type="button" 
                                    data-answer="{{ trim($option) }}"
                                    class="w-full text-left p-4 border-2 border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition answer-option"
                                    onclick="selectAnswer(this.dataset.answer, this)">
                                <span class="font-medium">{{ trim($option) }}</span>
                            </button>
                            @endif
                        @endforeach
                    </div>

                    @if($alreadyAnswered && !$quiz->show_answer)
                    <div class="mt-6 p-6 bg-linear-to-r from-blue-50 to-indigo-50 border-2 border-blue-400 rounded-lg shadow-lg">
                        <div class="flex items-center mb-3">
                            <svg class="h-8 w-8 text-blue-600 mr-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-xl font-bold text-blue-800">✓ Answer Submitted Successfully</p>
                                <p class="text-blue-600 mt-1">Waiting for admin to reveal the correct answer...</p>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center justify-center space-x-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                        </div>
                    </div>
                    @endif

                    @if($quiz->show_answer && $currentQuestion->correct_answer)
                    <div class="mt-6 p-6 bg-linear-to-r from-green-50 to-green-100 border-2 border-green-500 rounded-lg shadow-lg">
                        <div class="flex items-center mb-2">
                            <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-xl font-bold text-green-800">✅ Correct Answer Revealed</h3>
                        </div>
                        <p class="text-2xl font-bold text-green-900 mt-3">{{ $currentQuestion->correct_answer }}</p>
                        
                        @if($alreadyAnswered)
                        @php
                            $userAnswer = $quiz->participations()
                                ->where('school_id', Auth::user()->school_id)
                                ->first()
                                ->answers()
                                ->where('question_id', $currentQuestion->id)
                                ->first();
                        @endphp
                        @if($userAnswer)
                        <div class="mt-4 p-4 {{ $currentQuestion->isCorrectAnswer($userAnswer->answer_text) ? 'bg-green-100 border-green-500' : 'bg-red-100 border-red-500' }} border-2 rounded-lg">
                            <p class="font-semibold {{ $currentQuestion->isCorrectAnswer($userAnswer->answer_text) ? 'text-green-800' : 'text-red-800' }}">
                                Your Answer: {{ $userAnswer->answer_text }}
                            </p>
                            <p class="text-sm mt-1 {{ $currentQuestion->isCorrectAnswer($userAnswer->answer_text) ? 'text-green-700' : 'text-red-700' }}">
                                @if($currentQuestion->isCorrectAnswer($userAnswer->answer_text))
                                    ✅ Correct! +{{ $userAnswer->points_earned }} points
                                @else
                                    ❌ Incorrect
                                @endif
                            </p>
                        </div>
                        @endif
                        @endif
                    </div>
                    @endif

                    @else
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Answer:</label>
                        <textarea id="answer-input" rows="4" 
                                  class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Type your answer here..." {{ $alreadyAnswered ? 'disabled' : '' }}></textarea>
                        <button type="button" onclick="submitTextAnswer()" 
                                class="mt-3 w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium"
                                {{ $alreadyAnswered ? 'disabled' : '' }}>
                            Submit Answer
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Score Display -->
            <div class="flex justify-between items-center p-4 bg-indigo-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Current Score:</span>
                <span id="current-score" class="text-xl font-bold text-indigo-600">{{ $participation->total_score }} pts</span>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900">Quiz Completed!</h3>
            <p class="mt-2 text-gray-600">Final Score: {{ $participation->total_score }} points</p>
            <a href="{{ route('school.quizzes.results', $quiz) }}" class="mt-6 inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg">
                View Results
            </a>
        </div>
        @endif
    </div>
</div>

<script>
let timeLeft = {{ $quiz->question_timer }};
let timerInterval;
let questionId = {{ $currentQuestion->id ?? 'null' }};
let startTime = Date.now();
let alreadyAnswered = {{ $alreadyAnswered ? 'true' : 'false' }};
let refreshInterval;
let timerExpired = false;
let timerStarted = {{ $quiz->timer_started_at ? 'true' : 'false' }};
let selectedAnswer = null;
let answerSubmitted = false;

function selectAnswer(answer, buttonElement) {
    if (alreadyAnswered || answerSubmitted || timerExpired) return;
    
    // Remove previous selection
    document.querySelectorAll('.answer-option').forEach(btn => {
        btn.classList.remove('border-indigo-600', 'bg-indigo-100');
        btn.classList.add('border-gray-300');
    });
    
    // Highlight selected answer
    buttonElement.classList.remove('border-gray-300');
    buttonElement.classList.add('border-indigo-600', 'bg-indigo-100');
    
    // Store selected answer but don't submit yet - allow changing
    selectedAnswer = answer;
}

function startTimer() {
    if (alreadyAnswered || answerSubmitted) {
        // Don't start timer if already answered, just check for new questions
        checkForNewQuestion();
        return;
    }

    const timerElement = document.getElementById('timer');
    const timerBar = document.getElementById('timer-bar');
    const totalTime = {{ $quiz->question_timer }};

    timerInterval = setInterval(() => {
        timeLeft--;
        timerElement.textContent = timeLeft;
        
        const percentage = (timeLeft / totalTime) * 100;
        timerBar.style.width = percentage + '%';

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            timerExpired = true;
            
            // Auto-submit selected answer if any
            if (selectedAnswer && !answerSubmitted) {
                submitAnswer(selectedAnswer);
            } else {
                // Disable all answer options
                document.querySelectorAll('.answer-option').forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                });
                
                // Show timeout message
                showTimeoutMessage();
            }
        }
    }, 1000);
}

function waitForTimerToStart() {
    // Poll via AJAX to check if timer has started, only reload when needed
    const checkInterval = setInterval(() => {
        fetch('{{ route('school.quizzes.check-state', $quiz) }}', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.timer_started_at || data.current_question_id !== currentQuestionId) {
                clearInterval(checkInterval);
                window.location.reload();
            }
        })
        .catch(() => {});
    }, 3000);
}

function showTimeoutMessage() {
    const container = document.getElementById('quiz-container');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 p-6 rounded-lg shadow-2xl z-50 bg-red-500 text-white text-center';
    messageDiv.innerHTML = `
        <svg class="mx-auto h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-2xl font-bold">Time's Up!</h3>
        <p class="text-lg mt-2">Answer selection disabled</p>
    `;
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

function checkForNewQuestion() {
    // Check every 5 seconds if admin has moved to next question
    refreshInterval = setInterval(() => {
        window.location.reload();
    }, 5000);
}

function submitTextAnswer() {
    if (alreadyAnswered || timerExpired || answerSubmitted) return;
    
    const answerInput = document.getElementById('answer-input');
    const answer = answerInput.value.trim();
    
    if (!answer) {
        alert('Please provide an answer');
        return;
    }

    clearInterval(timerInterval);
    answerInput.disabled = true;
    
    submitAnswer(answer);
}

function submitAnswer(answer) {
    if (answerSubmitted) return;
    
    answerSubmitted = true;
    const timeTaken = Math.floor((Date.now() - startTime) / 1000);
    
    // Disable all options
    document.querySelectorAll('.answer-option').forEach(btn => {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    });

    fetch('{{ route('school.quizzes.submit', $quiz) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            question_id: questionId,
            answer: answer,
            time_taken: timeTaken
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update score
            document.getElementById('current-score').textContent = data.total_score + ' pts';
            
            // No immediate feedback - just show submission confirmation
            showSubmissionConfirmation();

            // Reload page to wait for admin to reveal answer or next question
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        answerSubmitted = false;
    });
}

function showSubmissionConfirmation() {
    const confirmDiv = document.createElement('div');
    confirmDiv.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-xl z-50 bg-blue-500 text-white animate-slide-in';
    confirmDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="font-bold">Answer Submitted!</p>
                <p class="text-sm text-blue-100">Reloading...</p>
            </div>
        </div>
    `;
    document.body.appendChild(confirmDiv);
    
    setTimeout(() => {
        confirmDiv.remove();
    }, 1300);
}

// Store current state for change detection
let currentQuestionId = {{ $currentQuestion->id ?? 'null' }};
let currentShowAnswer = {{ $quiz->show_answer ? 'true' : 'false' }};
let quizIsLive = {{ $quiz->is_live ? 'true' : 'false' }};

// Auto-refresh polling function
function startAutoRefreshPolling() {
    setInterval(() => {
        // Check for changes in quiz state
        fetch('{{ route('school.quizzes.check-state', $quiz) }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Check if quiz ended
            if (!data.is_live && quizIsLive) {
                // Quiz was just ended by admin
                showQuizEndedNotification();
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
                return;
            }
            
            // Check if question changed
            if (data.current_question_id !== currentQuestionId) {
                // Admin moved to different question
                showQuestionChangedNotification();
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return;
            }
            
            // Check if answer was revealed
            if (data.show_answer && !currentShowAnswer && alreadyAnswered) {
                // Admin revealed the answer
                showAnswerRevealedNotification();
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return;
            }
        })
        .catch(error => {
            console.log('Polling error:', error);
        });
    }, 2000); // Check every 2 seconds
}

function showQuizEndedNotification() {
    const notif = document.createElement('div');
    notif.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 p-8 rounded-lg shadow-2xl z-50 bg-red-500 text-white text-center';
    notif.innerHTML = `
        <svg class="mx-auto h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <h3 class="text-2xl font-bold">Quiz Ended</h3>
        <p class="text-lg mt-2">The admin has ended the quiz</p>
    `;
    document.body.appendChild(notif);
}

function showQuestionChangedNotification() {
    const notif = document.createElement('div');
    notif.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-xl z-50 bg-indigo-500 text-white';
    notif.innerHTML = `
        <div class="flex items-center">
            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <div>
                <p class="font-bold">New Question!</p>
                <p class="text-sm text-indigo-100">Loading...</p>
            </div>
        </div>
    `;
    document.body.appendChild(notif);
}

function showAnswerRevealedNotification() {
    const notif = document.createElement('div');
    notif.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-xl z-50 bg-green-500 text-white';
    notif.innerHTML = `
        <div class="flex items-center">
            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="font-bold">Answer Revealed!</p>
                <p class="text-sm text-green-100">Loading results...</p>
            </div>
        </div>
    `;
    document.body.appendChild(notif);
}

@if($currentQuestion)
@if($quiz->timer_started_at)
// Timer already started, calculate remaining time and sync
const timerStartedAt = new Date('{{ $quiz->timer_started_at->toIso8601String() }}');
const now = new Date();
const elapsed = Math.floor((now - timerStartedAt) / 1000);
timeLeft = Math.max(0, {{ $quiz->question_timer }} - elapsed);

if (timeLeft > 0 && !alreadyAnswered) {
    startTimer();
} else if (timeLeft <= 0 && !alreadyAnswered) {
    timerExpired = true;
    // Disable all answer options
    document.querySelectorAll('.answer-option').forEach(btn => {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    });
    const answerInput = document.getElementById('answer-input');
    if (answerInput) {
        answerInput.disabled = true;
    }
}
@else
// Timer not started yet, wait for admin to read question and start timer
if (!alreadyAnswered) {
    waitForTimerToStart();
}
@endif

// Start auto-refresh polling
startAutoRefreshPolling();
@endif
</script>
@endsection
