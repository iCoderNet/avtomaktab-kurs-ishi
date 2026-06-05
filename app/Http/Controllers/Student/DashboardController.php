<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\ExamResult;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            return view('student.no-profile');
        }

        $stats = [
            'total_lessons' => Lesson::where('student_id', $student->id)->count(),
            'completed_lessons' => Lesson::where('student_id', $student->id)
                ->where('status', 'completed')->count(),
            'attendance_rate' => $student->attendance_rate,
            'debt' => $student->debt,
            'payment_percent' => $student->payment_percentage,
            'exams_passed' => ExamResult::where('student_id', $student->id)
                ->where('result', 'passed')->count(),
        ];

        // Kelgusi darslar
        $upcomingLessons = Lesson::with('instructor.user', 'vehicle', 'schedule')
            ->where('student_id', $student->id)
            ->where('status', 'scheduled')
            ->whereDate('date', '>=', now())
            ->orderBy('date')->orderBy('start_time')
            ->take(5)->get();

        // So'nggi to'lovlar
        $recentPayments = Payment::where('student_id', $student->id)
            ->latest('payment_date')->take(5)->get();

        // Imtihon natijalari
        $examResults = ExamResult::with('exam')
            ->where('student_id', $student->id)
            ->latest()->take(5)->get();

        return view('student.dashboard', compact(
            'student', 'stats', 'upcomingLessons', 'recentPayments', 'examResults'
        ));
    }
}
