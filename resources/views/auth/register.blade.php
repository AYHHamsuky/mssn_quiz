@extends('layouts.app')

@section('title', 'Register - MSSN Quiz')

@section('content')
<div class="flex min-h-screen">
    <!-- Left Side - Registration Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-8 lg:p-12 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <div class="inline-flex w-16 h-16 bg-indigo-600 rounded-2xl items-center justify-center shadow-lg mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">MSSN Quiz</h1>
            </div>

            <!-- Welcome Text -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create your account</h2>
                <p class="text-gray-600">Register your school to get started</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">Please fix the following errors:</p>
                            <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- School Name -->
                <div>
                    <label for="school_name" class="block text-sm font-medium text-gray-700 mb-2">
                        School Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="school_name" 
                        id="school_name" 
                        value="{{ old('school_name') }}"
                        required 
                        autofocus
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
                        placeholder="Enter school name"
                    >
                    @error('school_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
                        placeholder="school@example.com"
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <input 
                        type="tel" 
                        name="phone" 
                        id="phone" 
                        value="{{ old('phone') }}"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
                        placeholder="+234 XXX XXX XXXX"
                    >
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        School Address <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <textarea 
                        name="address" 
                        id="address" 
                        rows="2"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 resize-none"
                        placeholder="Enter school address"
                    >{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
                        placeholder="Min. 8 characters"
                    >
                    <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters long</p>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
                        placeholder="Re-enter password"
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 transform hover:scale-[1.01] shadow-lg hover:shadow-xl"
                >
                    Create Account
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-gray-50 text-gray-500">Already have an account?</span>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-sm hover:shadow">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Sign in to existing account
                </a>
            </div>
        </div>
    </div>

    <!-- Right Side - Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-linear-to-br from-blue-600 via-indigo-600 to-purple-700 relative overflow-hidden">
        <!-- Animated Background Blobs -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 -left-4 w-96 h-96 bg-cyan-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
            <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-center items-center text-white p-12 w-full">
            <div class="max-w-lg">
                <!-- Logo -->
                <div class="mb-8">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-lg rounded-3xl flex items-center justify-center shadow-2xl border border-white/30">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>

                <h1 class="text-5xl font-bold mb-6 leading-tight">
                    Join the<br/>Competition
                </h1>
                
                <p class="text-xl text-white/90 mb-10 leading-relaxed">
                    Create your account and start competing in quizzes. Track your progress and climb the leaderboard.
                </p>

                <!-- Features -->
                <div class="space-y-5">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Quick Registration</h3>
                            <p class="text-white/80">Get started in just a few minutes</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Instant Access</h3>
                            <p class="text-white/80">Start participating immediately after registration</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Free to Join</h3>
                            <p class="text-white/80">No hidden fees or charges</p>
                        </div>
                    </div>
                </div>

                <!-- Trust Indicators -->
                <div class="mt-12 pt-8 border-t border-white/20">
                    <div class="flex items-center justify-around text-center">
                        <div>
                            <div class="text-3xl font-bold mb-1">500+</div>
                            <div class="text-sm text-white/80">Schools Registered</div>
                        </div>
                        <div class="w-px h-12 bg-white/20"></div>
                        <div>
                            <div class="text-3xl font-bold mb-1">10K+</div>
                            <div class="text-sm text-white/80">Active Students</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes blob {
        0%, 100% { 
            transform: translate(0, 0) scale(1); 
        }
        33% { 
            transform: translate(30px, -50px) scale(1.1); 
        }
        66% { 
            transform: translate(-20px, 20px) scale(0.9); 
        }
    }
    
    .animate-blob {
        animation: blob 7s infinite;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>
@endsection