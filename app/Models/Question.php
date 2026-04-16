<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_audio_path',
        'options',
        'correct_answer',
        'passage_text',
        'order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function isCorrectAnswer($answer)
    {
        $cleanAnswer = strtolower(trim($answer));
        $cleanCorrect = strtolower(trim($this->correct_answer));

        // Direct match
        if ($cleanAnswer === $cleanCorrect) {
            return true;
        }

        // Extract letter from answer if it starts with a letter followed by period or parenthesis
        // e.g., "b. 462 cm²" or "b) 462 cm²" should match "b"
        if (preg_match('/^([a-d])[\.\)\s]/', $cleanAnswer, $matches)) {
            $extractedLetter = strtolower($matches[1]);
            if ($extractedLetter === $cleanCorrect) {
                return true;
            }
        }

        // Check if the correct answer is a letter and the answer contains that option
        if (strlen($cleanCorrect) === 1 && preg_match('/^[a-d]$/', $cleanCorrect)) {
            // Find the matching option text
            if ($this->options) {
                foreach ($this->options as $option) {
                    $cleanOption = strtolower(trim($option));
                    // If this option starts with the correct letter and matches the answer
                    if (preg_match('/^' . $cleanCorrect . '[\.\)\s]/', $cleanOption)) {
                        if ($cleanOption === $cleanAnswer) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
