@extends('layouts.app')

@section('title', 'Registered Schools')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Registered Schools</h1>
            <p class="text-gray-600 mt-1">Manage all schools participating in quizzes</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Total Schools</p>
            <p class="text-3xl font-bold text-gray-900">{{ $schools->count() }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Active Schools</p>
            <p class="text-3xl font-bold text-green-600">{{ $schools->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Total Users</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600">Total Participations</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalParticipations }}</p>
        </div>
    </div>

    <!-- Schools List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">All Schools</h2>
        </div>
        
        @if($schools->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($schools as $school)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 font-semibold">{{ substr($school->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $school->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($school->address, 30) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $school->registration_code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $school->email }}</div>
                            <div class="text-sm text-gray-500">{{ $school->phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-medium text-gray-900">{{ $school->users->count() }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-medium text-gray-900">{{ $school->quizParticipations->count() }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-bold text-indigo-600">{{ $school->quizParticipations->sum('total_score') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($school->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $school->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No Schools Registered</h3>
            <p class="mt-2 text-sm text-gray-500">Schools will appear here once they register for the quiz system.</p>
        </div>
        @endif
    </div>

    <!-- Top Performing Schools -->
    @if($topSchools->count() > 0)
    <div class="mt-8 bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-linear-to-r from-yellow-50 to-orange-50">
            <h2 class="text-xl font-semibold flex items-center">
                <svg class="h-6 w-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                Top Performing Schools
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($topSchools as $index => $school)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full {{ $index === 0 ? 'bg-yellow-400' : ($index === 1 ? 'bg-gray-400' : 'bg-orange-400') }}">
                        <span class="text-white font-bold text-lg">#{{ $index + 1 }}</span>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $school->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $school->quizParticipations->count() }} quizzes completed</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-indigo-600">{{ $school->total_score }}</p>
                        <p class="text-sm text-gray-500">total points</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
