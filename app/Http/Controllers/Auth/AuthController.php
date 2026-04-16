<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:schools',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create school
        $school = School::create([
            'name' => $validated['school_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'registration_code' => School::generateRegistrationCode(),
            'is_active' => true,
        ]);

        // Create user for school
        $user = User::create([
            'name' => $validated['school_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'school',
            'school_id' => $school->id,
        ]);

        Auth::login($user);

        return redirect()->route('school.dashboard')
            ->with('success', 'Registration successful! Your registration code is: ' . $school->registration_code);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('school.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
