<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id', 'student_id', 'instructor_id', 'vehicle_id',
        'type', 'date', 'start_time', 'end_time', 'status',
        'duration_minutes', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
