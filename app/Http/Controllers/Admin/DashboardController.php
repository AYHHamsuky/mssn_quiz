<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\School;
use App\Models\QuizParticipation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_quizzes' => Quiz::count(),
            'active_quizzes' => Quiz::where('is_active', true)->count(),
            'live_quizzes' => Quiz::where('is_live', true)->count(),
            'total_schools' => School::count(),
            'active_schools' => School::where('is_active', true)->count(),
            'total_participations' => QuizParticipation::count(),
        ];

        $recentQuizzes = Quiz::latest()->take(5)->get();
        $liveQuizzes = Quiz::where('is_live', true)->with(['participations.school'])->get();

        return view('admin.dashboard', compact('stats', 'recentQuizzes', 'liveQuizzes'));
    }

    public function leaderboard(Request $request)
    {
        $quizId = $request->input('quiz_id');
        
        $query = QuizParticipation::with(['school', 'quiz'])
            ->orderBy('total_score', 'desc');

        if ($quizId) {
            $query->where('quiz_id', $quizId);
        }

        $leaderboard = $query->paginate(20);
        $quizzes = Quiz::all();

        return view('admin.leaderboard', compact('leaderboard', 'quizzes', 'quizId'));
    }

    public function schools()
    {
        $schools = School::with(['users', 'quizParticipations'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalUsers = $schools->sum(function($school) {
            return $school->users->count();
        });

        $totalParticipations = $schools->sum(function($school) {
            return $school->quizParticipations->count();
        });

        // Top 5 performing schools by total score
        $topSchools = School::with('quizParticipations')
            ->get()
            ->map(function($school) {
                $school->total_score = $school->quizParticipations->sum('total_score');
                return $school;
            })
            ->sortByDesc('total_score')
            ->take(5);

        return view('admin.schools', compact('schools', 'totalUsers', 'totalParticipations', 'topSchools'));
    }
}
