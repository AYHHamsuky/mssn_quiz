@extends('layouts.app')

@section('title', 'Performance Report')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Performance Report</h1>
        <p class="text-gray-600 mt-1">{{ auth()->user()->school->name }}</p>
    </div>

    <!-- Overall Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Total Quizzes</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalQuizzes }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Completed</p>
            <p class="text-3xl font-bold text-green-600">{{ $completedQuizzes }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Total Points</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $totalPoints }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Average Score</p>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($averageScore, 1) }}%</p>
        </div>
    </div>

    <!-- Performance by Subject -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Performance by Subject</h2>
        </div>
        <div class="p-6">
            @if($subjectPerformance->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quizzes Taken</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Points</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Score</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($subjectPerformance as $subject)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $subject->subject ?? 'General' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $subject->quiz_count }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $subject->total_points }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($subject->avg_score, 1) }}%</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ min($subject->avg_score, 100) }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <p class="mt-4 text-gray-500">No quiz data available yet</p>
                <p class="text-sm text-gray-400">Complete some quizzes to see your performance</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Quiz History -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Recent Quiz History</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recentParticipations as $participation)
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900">{{ $participation->quiz->title }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $participation->quiz->subject }} - {{ $participation->quiz->getTypeLabel() }}</p>
                        <div class="mt-2 flex items-center space-x-4 text-sm">
                            <span class="text-gray-500">
                                <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $participation->completed_at ? $participation->completed_at->format('M d, Y') : 'In Progress' }}
                            </span>
                            <span class="text-gray-500">
                                <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $participation->correct_answers }} correct / {{ $participation->quiz->questions->count() }} questions
                            </span>
                        </div>
                    </div>
                    <div class="ml-4 text-right">
                        <p class="text-2xl font-bold text-indigo-600">{{ $participation->total_score }}</p>
                        <p class="text-sm text-gray-500">points</p>
                        @if($participation->completed_at)
                        <a href="{{ route('school.quizzes.results', $participation->quiz) }}" 
                           class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-800">
                            View Details →
                        </a>
                        @else
                        <a href="{{ route('school.quizzes.participate', $participation->quiz) }}" 
                           class="mt-2 inline-block text-sm text-green-600 hover:text-green-800">
                            Continue Quiz →
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                <p>No quiz history available</p>
                <a href="{{ route('school.quizzes.index') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-800">
                    Browse Available Quizzes →
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
