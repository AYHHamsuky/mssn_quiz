@extends('layouts.app')

@section('title', 'Edit Quiz')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Quiz</h1>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Quiz Title</label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           value="{{ old('title', $quiz->title) }}">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description', $quiz->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Quiz Type</label>
                        <select name="type" id="type" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="general" {{ old('type', $quiz->type) == 'general' ? 'selected' : '' }}>General Subject</option>
                            <option value="debate" {{ old('type', $quiz->type) == 'debate' ? 'selected' : '' }}>Debate</option>
                            <option value="impromptu_speech" {{ old('type', $quiz->type) == 'impromptu_speech' ? 'selected' : '' }}>Impromptu Speech</option>
                            <option value="essay" {{ old('type', $quiz->type) == 'essay' ? 'selected' : '' }}>Essay</option>
                            <option value="arabic_passage" {{ old('type', $quiz->type) == 'arabic_passage' ? 'selected' : '' }}>Arabic Passage Reading</option>
                            <option value="english_passage" {{ old('type', $quiz->type) == 'english_passage' ? 'selected' : '' }}>English Passage Reading</option>
                        </select>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject (Optional)</label>
                        <input type="text" name="subject" id="subject"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               value="{{ old('subject', $quiz->subject) }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" required min="1"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               value="{{ old('duration_minutes', $quiz->duration_minutes) }}">
                    </div>

                    <div>
                        <label for="question_timer" class="block text-sm font-medium text-gray-700">Time per Question (sec)</label>
                        <input type="number" name="question_timer" id="question_timer" required min="10" max="300"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               value="{{ old('question_timer', $quiz->question_timer) }}">
                    </div>

                    <div>
                        <label for="points_per_question" class="block text-sm font-medium text-gray-700">Points per Question</label>
                        <input type="number" name="points_per_question" id="points_per_question" required min="1"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               value="{{ old('points_per_question', $quiz->points_per_question) }}">
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700">
                        Update Quiz
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
