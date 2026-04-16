<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_participation_id',
        'question_id',
        'answer_text',
        'is_correct',
        'points_earned',
        'time_taken',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function quizParticipation()
    {
        return $this->belongsTo(QuizParticipation::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
