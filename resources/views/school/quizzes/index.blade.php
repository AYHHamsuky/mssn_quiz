@extends('layouts.app')

@section('title', 'Available Quizzes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Available Quizzes</h1>
        <p class="mt-1 text-sm text-gray-600">Browse and participate in live and upcoming quizzes</p>
    </div>

    <!-- Live Quizzes -->
    @if($liveQuizzes->count() > 0)
    <div class="mb-8">
        <div class="flex items-center space-x-2 mb-4">
            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
            <h2 class="text-xl font-semibold text-gray-900">Live Quizzes</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($liveQuizzes as $quiz)
            <div class="group bg-white border-2 border-green-300 shadow-sm rounded-xl overflow-hidden hover:shadow-lg hover:border-green-400 transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>
                            LIVE NOW
                        </span>
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $quiz->questions->count() }} questions</span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $quiz->title }}</h3>
                    <p class="text-sm text-gray-600 mb-1">{{ $quiz->getTypeLabel() }}</p>
                    @if($quiz->subject)
                    <p class="text-sm text-gray-500 mb-4">{{ $quiz->subject }}</p>
                    @endif
                    
                    <div class="mb-5 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $quiz->question_timer }}s per question
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            {{ $quiz->points_per_question }} points each
                        </div>
                    </div>

                    @php
                        $participation = $quiz->participations->where('school_id', Auth::user()->school_id)->first();
                    @endphp

                    @if($participation)
                        @if($participation->status === 'completed')
                            <a href="{{ route('school.quizzes.results', $quiz) }}" class="block w-full text-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-150">
                                View Results
                            </a>
                        @elseif($participation->status === 'in_progress')
                            <a href="{{ route('school.quizzes.participate', $quiz) }}" class="block w-full text-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-150 pulse-button">
                                Continue Quiz
                            </a>
                        @else
                            <a href="{{ route('school.quizzes.participate', $quiz) }}" class="block w-full text-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150 pulse-button">
                                Start Quiz
                            </a>
                        @endif
                    @else
                        <form action="{{ route('school.quizzes.register', $quiz) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                Register & Start
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Upcoming Quizzes -->
    @if($upcomingQuizzes->count() > 0)
    <div>
        <div class="flex items-center space-x-2 mb-4">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h2 class="text-xl font-semibold text-gray-900">Upcoming Quizzes</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($upcomingQuizzes as $quiz)
            <div class="group bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden hover:shadow-md hover:border-gray-300 transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            UPCOMING
                        </span>
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $quiz->questions->count() }} questions</span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $quiz->title }}</h3>
                    <p class="text-sm text-gray-600 mb-1">{{ $quiz->getTypeLabel() }}</p>
                    @if($quiz->subject)
                    <p class="text-sm text-gray-500 mb-4">{{ $quiz->subject }}</p>
                    @endif
                    
                    <div class="mb-5 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $quiz->start_time->format('M d, Y H:i') }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $quiz->question_timer }}s per question
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            {{ $quiz->points_per_question }} points each
                        </div>
                    </div>

                    @php
                        $participation = $quiz->participations->where('school_id', Auth::user()->school_id)->first();
                    @endphp

                    @if($participation)
                        <button disabled class="w-full px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-500 bg-gray-100 cursor-not-allowed">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Registered
                        </button>
                    @else
                        <form action="{{ route('school.quizzes.register', $quiz) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                Register Now
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if($liveQuizzes->count() === 0 && $upcomingQuizzes->count() === 0)
    <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-1">No quizzes available</h3>
        <p class="text-sm text-gray-600">Check back later for new quizzes.</p>
    </div>
    @endif
</div>

<style>
@keyframes pulse-scale {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}
.pulse-button {
    animation: pulse-scale 2s ease-in-out infinite;
}
</style>
@endsection
