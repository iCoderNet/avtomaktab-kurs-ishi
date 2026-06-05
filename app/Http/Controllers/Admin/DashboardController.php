<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Vehicle;
use App\Models\Payment;
use App\Models\Exam;
use App\Models\ExamResult;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Asosiy statistika
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('status', 'active')->count(),
            'total_instructors' => Instructor::where('status', 'active')->count(),
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'monthly_revenue' => Payment::whereMonth('payment_date', now()->month)
                ->where('status', 'completed')->sum('amount'),
            'total_debt' => Student::selectRaw('SUM(total_fee - paid_amount) as debt')
                ->where('status', 'active')->value('debt') ?? 0,
            'exams_this_month' => Exam::whereMonth('exam_date', now()->month)->count(),
        ];

        // Oxirgi 6 oy daromad (grafik uchun)
        $revenueData = [];
        $revenueLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenueData[] = Payment::whereYear('payment_date', $date->year)
                ->whereMonth('payment_date', $date->month)
                ->where('status', 'completed')
                ->sum('amount');
            $revenueLabels[] = $date->locale('uz')->translatedFormat('M Y');
        }

        // So'nggi 6 oy - har oy oxiridagi umumiy o'quvchilar soni (kumulyativ)
        $studentData = [];
        $studentLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i)->endOfMonth();
            $studentData[] = Student::where('enrollment_date', '<=', $date->toDateString())->count();
            $studentLabels[] = $date->locale('uz')->translatedFormat('M Y');
        }

        // Yaqinda to'liq o'quvchilar
        $recentStudents = Student::with('user', 'instructor.user')
            ->latest()->take(5)->get();

        // Qarz bo'lgan o'quvchilar
        $debtStudents = Student::with('user')
            ->whereRaw('total_fee > paid_amount')
            ->where('status', 'active')
            ->orderByRaw('total_fee - paid_amount DESC')
            ->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'revenueData', 'revenueLabels',
            'studentData', 'studentLabels',
            'recentStudents', 'debtStudents'
        ));
    }
}
