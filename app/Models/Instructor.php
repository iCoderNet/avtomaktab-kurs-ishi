<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'employee_number', 'license_categories', 'driving_license_number',
        'driving_license_expiry', 'experience_years', 'status', 'hire_date',
        'salary', 'max_students', 'notes',
    ];

    protected $casts = [
        'license_categories' => 'array',
        'driving_license_expiry' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class, 'assigned_instructor_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // Joriy o'quvchilar soni
    public function getActiveStudentsCountAttribute(): int
    {
        return $this->students()->where('status', 'active')->count();
    }

    // Bo'sh joy bormi?
    public function getHasAvailableSlotAttribute(): bool
    {
        return $this->active_students_count < $this->max_students;
    }
}
