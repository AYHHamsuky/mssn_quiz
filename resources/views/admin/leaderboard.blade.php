@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Leaderboard</h1>
        <p class="text-gray-600 mt-1">School rankings and performance</p>
    </div>

    <!-- Filter by Quiz -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <form method="GET" action="{{ route('admin.leaderboard') }}" class="flex items-end space-x-4">
            <div class="flex-1">
                <label for="quiz_id" class="block text-sm font-medium text-gray-700 mb-2">Filter by Quiz</label>
                <select name="quiz_id" id="quiz_id" 
                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Quizzes</option>
                    @foreach($quizzes as $quiz)
                    <option value="{{ $quiz->id }}" {{ $quizId == $quiz->id ? 'selected' : '' }}>
                        {{ $quiz->title }} - {{ $quiz->subject }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md">
                Filter
            </button>
            @if($quizId)
            <a href="{{ route('admin.leaderboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-md">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Leaderboard -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-linear-to-r from-yellow-50 to-orange-50">
            <h2 class="text-xl font-semibold flex items-center">
                <svg class="h-6 w-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                {{ $quizId ? 'Quiz Leaderboard' : 'Overall Leaderboard' }}
            </h2>
        </div>

        @if($leaderboard->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correct Answers</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($leaderboard as $index => $participation)
                    <tr class="hover:bg-gray-50 {{ $index < 3 ? 'bg-yellow-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($index === 0)
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-yellow-400">
                                    <span class="text-white font-bold">🥇</span>
                                </div>
                                @elseif($index === 1)
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gray-400">
                                    <span class="text-white font-bold">🥈</span>
                                </div>
                                @elseif($index === 2)
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-orange-400">
                                    <span class="text-white font-bold">🥉</span>
                                </div>
                                @else
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gray-200">
                                    <span class="text-gray-600 font-semibold">#{{ $index + 1 }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 font-semibold">{{ substr($participation->school->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $participation->school->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $participation->school->registration_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $participation->quiz->title }}</div>
                            <div class="text-sm text-gray-500">{{ $participation->quiz->subject }} - {{ $participation->quiz->getTypeLabel() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-2xl font-bold text-indigo-600">{{ $participation->total_score }}</div>
                            <div class="text-xs text-gray-500">points</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm font-medium text-gray-900">{{ $participation->correct_answers }}</div>
                            <div class="text-xs text-gray-500">/ {{ $participation->total_questions }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($participation->status === 'completed')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                In Progress
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $participation->completed_at ? $participation->completed_at->format('M d, Y H:i') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $leaderboard->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No Results Found</h3>
            <p class="mt-2 text-sm text-gray-500">
                {{ $quizId ? 'No participations found for the selected quiz.' : 'No quiz participations available yet.' }}
            </p>
        </div>
        @endif
    </div>

    <!-- Summary Statistics (if specific quiz selected) -->
    @if($quizId && $leaderboard->count() > 0)
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Total Participants</p>
            <p class="text-3xl font-bold text-gray-900">{{ $leaderboard->total() }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Highest Score</p>
            <p class="text-3xl font-bold text-green-600">{{ $leaderboard->first()->total_score ?? 0 }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Average Score</p>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($leaderboard->avg('total_score'), 1) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Completion Rate</p>
            @php
                $completedCount = $leaderboard->where('status', 'completed')->count();
                $completionRate = $leaderboard->count() > 0 ? ($completedCount / $leaderboard->count()) * 100 : 0;
            @endphp
            <p class="text-3xl font-bold text-indigo-600">{{ number_format($completionRate, 1) }}%</p>
        </div>
    </div>
    @endif
</div>
@endsection
