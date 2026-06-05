<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'lesson_id', 'instructor_id', 'type',
        'score', 'grade_letter', 'comment', 'graded_at',
    ];

    protected $casts = [
        'graded_at' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    // Ball asosida harf belgisi
    public static function getLetterGrade(int $score): string
    {
        return match(true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            $score >= 60 => 'D',
            default => 'F',
        };
    }
}
