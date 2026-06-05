<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id', 'student_id', 'score', 'correct_answers', 'total_questions',
        'result', 'attempt_number', 'answers', 'started_at', 'finished_at',
        'examiner_notes', 'examined_by',
    ];

    protected $casts = [
        'answers' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function examinedBy()
    {
        return $this->belongsTo(User::class, 'examined_by');
    }
}
