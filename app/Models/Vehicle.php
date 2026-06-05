<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number', 'brand', 'model', 'year', 'color', 'vin_number',
        'license_category', 'status', 'mileage', 'last_maintenance_date',
        'next_maintenance_date', 'insurance_expiry', 'technical_inspection_expiry',
        'notes', 'assigned_instructor_id',
    ];

    protected $casts = [
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'insurance_expiry' => 'date',
        'technical_inspection_expiry' => 'date',
    ];

    public function assignedInstructor()
    {
        return $this->belongsTo(Instructor::class, 'assigned_instructor_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    // Sug'urta muddati o'tganmi?
    public function getIsInsuranceExpiredAttribute(): bool
    {
        return $this->insurance_expiry && $this->insurance_expiry->isPast();
    }

    // Texnik ko'rik muddati o'tganmi?
    public function getIsInspectionExpiredAttribute(): bool
    {
        return $this->technical_inspection_expiry && $this->technical_inspection_expiry->isPast();
    }

    // To'liq nomi
    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model} ({$this->year}) - {$this->plate_number}";
    }
}
