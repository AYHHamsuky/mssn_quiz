<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizParticipation extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'quiz_id',
        'total_score',
        'correct_answers',
        'wrong_answers',
        'started_at',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function start()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function addPoints($points)
    {
        $this->increment('total_score', $points);
        $this->increment('correct_answers');
    }

    public function recordWrongAnswer()
    {
        $this->increment('wrong_answers');
    }
}
