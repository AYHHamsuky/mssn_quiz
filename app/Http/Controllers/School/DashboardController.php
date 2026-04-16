<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        
        $stats = [
            'total_participations' => $school->quizParticipations()->count(),
            'completed_quizzes' => $school->quizParticipations()->where('status', 'completed')->count(),
            'in_progress_quizzes' => $school->quizParticipations()->where('status', 'in_progress')->count(),
            'total_score' => $school->quizParticipations()->sum('total_score'),
        ];

        $participations = $school->quizParticipations()
            ->with('quiz')
            ->latest()
            ->take(10)
            ->get();

        $availableQuizzes = Quiz::where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->whereDoesntHave('participations', function($query) use ($school) {
                $query->where('school_id', $school->id);
            })
            ->get();

        return view('school.dashboard', compact('stats', 'participations', 'availableQuizzes'));
    }

    public function performance()
    {
        $school = Auth::user()->school;
        
        // Get all participations with quiz and questions eager loaded
        $participations = $school->quizParticipations()->with('quiz.questions')->get();
        
        // Calculate overall statistics
        $totalQuizzes = $participations->count();
        $completedQuizzes = $participations->where('status', 'completed')->count();
        $totalPoints = $participations->sum('total_score');
        
        // Calculate average score percentage
        $averageScore = 0;
        if ($completedQuizzes > 0) {
            $totalPossiblePoints = $participations->where('status', 'completed')->sum(function($p) {
                return $p->quiz->questions->count() * $p->quiz->points_per_question;
            });
            $averageScore = $totalPossiblePoints > 0 ? ($totalPoints / $totalPossiblePoints) * 100 : 0;
        }
        
        // Performance by subject
        $subjectPerformance = $participations->where('status', 'completed')
            ->groupBy('quiz.subject')
            ->map(function($items) {
                $totalPoints = $items->sum('total_score');
                $totalPossible = $items->sum(function($p) {
                    return $p->quiz->questions->count() * $p->quiz->points_per_question;
                });
                
                return (object)[
                    'subject' => $items->first()->quiz->subject,
                    'quiz_count' => $items->count(),
                    'total_points' => $totalPoints,
                    'avg_score' => $totalPossible > 0 ? ($totalPoints / $totalPossible) * 100 : 0,
                ];
            });
        
        // Recent participations
        $recentParticipations = $school->quizParticipations()
            ->with('quiz.questions')
            ->latest()
            ->take(10)
            ->get();

        return view('school.performance', compact(
            'totalQuizzes',
            'completedQuizzes',
            'totalPoints',
            'averageScore',
            'subjectPerformance',
            'recentParticipations'
        ));
    }
}
