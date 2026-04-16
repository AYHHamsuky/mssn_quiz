<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MSSN Quiz Application')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 antialiased">
    @auth
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h1 class="text-xl font-bold text-gray-900">MSSN Quiz</h1>
                        </div>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="hidden sm:flex sm:space-x-1">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.quizzes.index') }}" class="@if(request()->routeIs('admin.quizzes.*')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
                                Quizzes
                            </a>
                            <a href="{{ route('admin.leaderboard') }}" class="@if(request()->routeIs('admin.leaderboard')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
                                Leaderboard
                            </a>
                            <a href="{{ route('admin.schools') }}" class="@if(request()->routeIs('admin.schools')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
                                Schools
                            </a>
                        @else
                            <a href="{{ route('school.dashboard') }}" class="@if(request()->routeIs('school.dashboard')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
                                Dashboard
                            </a>
                            <a href="{{ route('school.quizzes.index') }}" class="@if(request()->routeIs('school.quizzes.*')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
                                Quizzes
                            </a>
                            <a href="{{ route('school.performance') }}" class="@if(request()->routeIs('school.performance')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-150">
                                Performance
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Right Side - User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center space-x-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-600">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div class="sm:hidden border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif block px-3 py-2 rounded-lg text-base font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.quizzes.index') }}" class="@if(request()->routeIs('admin.quizzes.*')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif block px-3 py-2 rounded-lg text-base font-medium">
                        Quizzes
                    </a>
                    <a href="{{ route('admin.leaderboard') }}" class="@if(request()->routeIs('admin.leaderboard')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif block px-3 py-2 rounded-lg text-base font-medium">
                        Leaderboard
                    </a>
                    <a href="{{ route('admin.schools') }}" class="@if(request()->routeIs('admin.schools')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif block px-3 py-2 rounded-lg text-base font-medium">
                        Schools
                    </a>
                @else
                    <a href="{{ route('school.dashboard') }}" class="@if(request()->routeIs('school.dashboard')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif block px-3 py-2 rounded-lg text-base font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('school.quizzes.index') }}" class="@if(request()->routeIs('school.quizzes.*')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif block px-3 py-2 rounded-lg text-base font-medium">
                        Quizzes
                    </a>
                    <a href="{{ route('school.performance') }}" class="@if(request()->routeIs('school.performance')) bg-gray-100 text-gray-900 @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif block px-3 py-2 rounded-lg text-base font-medium">
                        Performance
                    </a>
                @endif
            </div>
        </div>
    </nav>
    @endauth

    <main class="@auth min-h-screen py-8 @endauth">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
