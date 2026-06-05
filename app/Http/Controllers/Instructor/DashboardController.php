<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Lesson;
use App\Models\Student;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $instructor = auth()->user()->instructor;

        if (!$instructor) {
            abort(403, "Instruktor profili topilmadi.");
        }

        $today = Carbon::today();
        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeekEnd = Carbon::now()->endOfWeek();

        $stats = [
            'my_students' => Student::where('instructor_id', $instructor->id)
                ->where('status', 'active')->count(),
            'today_lessons' => Lesson::where('instructor_id', $instructor->id)
                ->whereDate('date', $today)->count(),
            'week_lessons' => Lesson::where('instructor_id', $instructor->id)
                ->whereBetween('date', [$thisWeekStart, $thisWeekEnd])->count(),
            'total_hours' => Lesson::where('instructor_id', $instructor->id)
                ->where('status', 'completed')->sum('duration_minutes') / 60,
        ];

        // Bugungi darslar
        $todayLessons = Lesson::with('student.user', 'vehicle')
            ->where('instructor_id', $instructor->id)
            ->whereDate('date', $today)
            ->orderBy('start_time')
            ->get();

        // Mening o'quvchilarim
        $myStudents = Student::with('user')
            ->where('instructor_id', $instructor->id)
            ->where('status', 'active')
            ->take(6)->get();

        // Haftalik dars jadvali
        $weeklySchedule = Lesson::with('student.user')
            ->where('instructor_id', $instructor->id)
            ->whereBetween('date', [$thisWeekStart, $thisWeekEnd])
            ->orderBy('date')->orderBy('start_time')
            ->get()->groupBy(fn($l) => $l->date->format('Y-m-d'));

        return view('instructor.dashboard', compact(
            'instructor', 'stats', 'todayLessons', 'myStudents', 'weeklySchedule'
        ));
    }
}
