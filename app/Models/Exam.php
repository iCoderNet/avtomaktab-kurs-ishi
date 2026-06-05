<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'license_category', 'exam_date', 'duration_minutes',
        'passing_score', 'total_questions', 'status', 'created_by', 'instructions',
    ];

    protected $casts = [
        'exam_date' => 'datetime',
    ];

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPassRateAttribute(): float
    {
        $total = $this->results()->whereIn('result', ['passed', 'failed'])->count();
        if ($total === 0) return 0;
        $passed = $this->results()->where('result', 'passed')->count();
        return round(($passed / $total) * 100, 1);
    }
}
