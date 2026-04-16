@extends('layouts.app')

@section('title', 'Quiz Results')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Quiz Results</h1>
        <p class="text-gray-600 mt-1">{{ $participation->quiz->title }}</p>
    </div>

    <!-- Score Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Your Score</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $participation->total_score }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Correct Answers</p>
            <p class="text-3xl font-bold text-green-600">{{ $participation->correct_answers }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Wrong Answers</p>
            <p class="text-3xl font-bold text-red-600">{{ $participation->wrong_answers }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Your Rank</p>
            <p class="text-3xl font-bold text-purple-600">#{{ $rank }}</p>
        </div>
    </div>

    <!-- Leaderboard -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">🏆 Leaderboard</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">School</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Correct</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wrong</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($leaderboard as $index => $item)
                    <tr class="@if($item->id === $participation->id) bg-indigo-50 @endif">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold @if($index < 3) text-yellow-600 @else text-gray-600 @endif">
                                @if($index === 0) 🥇
                                @elseif($index === 1) 🥈
                                @elseif($index === 2) 🥉
                                @else #{{ $index + 1 }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $item->school->name }}
                                @if($item->id === $participation->id)
                                    <span class="ml-2 text-xs text-indigo-600">(You)</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-indigo-600">{{ $item->total_score }} pts</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-green-600">{{ $item->correct_answers }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-red-600">{{ $item->wrong_answers }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Your Answers -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Your Answers</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($participation->answers as $index => $answer)
            <div class="p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        @if($answer->is_correct)
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-lg font-medium text-gray-900">Question {{ $index + 1 }}</h3>
                        <p class="text-gray-700 mt-1">{{ $answer->question->question_text }}</p>
                        <p class="mt-2 text-sm">
                            <span class="font-medium">Your answer:</span>
                            <span class="@if($answer->is_correct) text-green-600 @else text-red-600 @endif">
                                {{ $answer->answer_text }}
                            </span>
                        </p>
                        @if(!$answer->is_correct && $answer->question->correct_answer)
                        <p class="mt-1 text-sm">
                            <span class="font-medium">Correct answer:</span>
                            <span class="text-green-600">{{ $answer->question->correct_answer }}</span>
                        </p>
                        @endif
                        <p class="mt-1 text-sm text-gray-500">
                            Points earned: <span class="font-medium">{{ $answer->points_earned }}</span>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('school.dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md inline-block">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection
