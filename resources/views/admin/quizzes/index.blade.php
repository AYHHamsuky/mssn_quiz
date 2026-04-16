@extends('layouts.app')

@section('title', 'All Quizzes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">All Quizzes</h1>
        <a href="{{ route('admin.quizzes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md font-medium">
            + Create New Quiz
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participants</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($quizzes as $quiz)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $quiz->title }}</div>
                        @if($quiz->subject)
                        <div class="text-sm text-gray-500">{{ $quiz->subject }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-900">{{ $quiz->getTypeLabel() }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $quiz->questions->count() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $quiz->participations->count() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div>{{ $quiz->start_time->format('M d, Y') }}</div>
                        <div class="text-xs">{{ $quiz->start_time->format('H:i') }} - {{ $quiz->end_time->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($quiz->is_live)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                🔴 Live
                            </span>
                        @elseif($quiz->hasEnded())
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Ended
                            </span>
                        @elseif($quiz->hasStarted())
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Active
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Scheduled
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                        <a href="{{ route('admin.quizzes.show', $quiz) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        No quizzes found. <a href="{{ route('admin.quizzes.create') }}" class="text-indigo-600 hover:text-indigo-900">Create your first quiz</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection
