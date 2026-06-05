<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'group_id', 'student_number', 'passport_series', 'passport_number',
        'passport_issued_date', 'passport_issued_by', 'license_category',
        'instructor_id', 'status', 'enrollment_date', 'expected_graduation',
        'total_fee', 'paid_amount', 'notes', 'documents',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'expected_graduation' => 'date',
        'passport_issued_date' => 'date',
        'documents' => 'array',
        'total_fee' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Qarzini hisoblash
    public function getDebtAttribute(): float
    {
        return max(0, $this->total_fee - $this->paid_amount);
    }

    // To'lov foizini hisoblash
    public function getPaymentPercentageAttribute(): int
    {
        if ($this->total_fee <= 0) return 100;
        return min(100, (int)(($this->paid_amount / $this->total_fee) * 100));
    }

    // Davomat foizini hisoblash
    public function getAttendanceRateAttribute(): float
    {
        $total = $this->lessons()->count();
        if ($total === 0) return 0;
        $present = $this->attendances()->where('status', 'present')->count();
        return round(($present / $total) * 100, 1);
    }
}
