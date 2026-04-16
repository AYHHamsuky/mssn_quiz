<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'registration_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users for the school.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the quiz participations for the school.
     */
    public function quizParticipations()
    {
        return $this->hasMany(QuizParticipation::class);
    }

    /**
     * Get the quizzes this school is participating in.
     */
    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_participations')
            ->withPivot('total_score', 'correct_answers', 'wrong_answers', 'status')
            ->withTimestamps();
    }

    /**
     * Generate a unique registration code.
     */
    public static function generateRegistrationCode()
    {
        do {
            $code = 'SCH-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('registration_code', $code)->exists());

        return $code;
    }
}
