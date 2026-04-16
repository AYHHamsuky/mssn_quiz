<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'subject',
        'start_time',
        'end_time',
        'duration_minutes',
        'question_timer',
        'points_per_question',
        'is_live',
        'is_active',
        'current_question_id',
        'question_started_at',
        'timer_started_at',
        'show_answer',
        'completed_at',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'question_started_at' => 'datetime',
        'timer_started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_live' => 'boolean',
        'is_active' => 'boolean',
        'show_answer' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function currentQuestion()
    {
        return $this->belongsTo(Question::class, 'current_question_id');
    }

    public function participations()
    {
        return $this->hasMany(QuizParticipation::class);
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'quiz_participations')
            ->withPivot('total_score', 'correct_answers', 'wrong_answers', 'status')
            ->withTimestamps();
    }

    public function isLiveNow()
    {
        $now = now();
        return $this->is_live && 
               $this->start_time <= $now && 
               $this->end_time >= $now;
    }

    public function hasStarted()
    {
        return $this->start_time <= now();
    }

    public function hasEnded()
    {
        return $this->end_time < now();
    }

    public function isCompleted()
    {
        return $this->completed_at !== null;
    }

    public function getTypeLabel()
    {
        return match($this->type) {
            'general' => 'General Subject',
            'debate' => 'Debate',
            'impromptu_speech' => 'Impromptu Speech',
            'essay' => 'Essay',
            'arabic_passage' => 'Arabic Passage Reading',
            'english_passage' => 'English Passage Reading',
            default => $this->type,
        };
    }
}
