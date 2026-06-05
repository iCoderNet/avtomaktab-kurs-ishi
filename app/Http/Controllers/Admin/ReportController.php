<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\ExamResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Exports\PaymentsExport;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $stats = [
            'new_students' => Student::whereBetween('enrollment_date', [$startDate, $endDate])->count(),
            'total_revenue' => Payment::whereBetween('payment_date', [$startDate, $endDate])
                ->where('status', 'completed')->sum('amount'),
            'total_debt' => Student::whereRaw('total_fee > paid_amount')->where('status', 'active')
                ->selectRaw('SUM(total_fee - paid_amount) as debt')->value('debt') ?? 0,
            'exams_passed' => ExamResult::where('result', 'passed')
                ->whereBetween('created_at', [$startDate, $endDate])->count(),
            'attendance_rate' => Attendance::whereBetween('created_at', [$startDate, $endDate])->count() > 0
                ? round(Attendance::where('status', 'present')->whereBetween('created_at', [$startDate, $endDate])->count()
                    / Attendance::whereBetween('created_at', [$startDate, $endDate])->count() * 100, 1)
                : 0,
        ];

        // Oylik to'lovlar
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyRevenue[] = [
                'label' => $date->locale('uz')->translatedFormat('M Y'),
                'amount' => Payment::whereYear('payment_date', $date->year)
                    ->whereMonth('payment_date', $date->month)
                    ->where('status', 'completed')->sum('amount'),
            ];
        }

        return view('admin.reports.index', compact('stats', 'startDate', 'endDate', 'monthlyRevenue'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $students = Student::with('user', 'instructor.user')->get();
        $payments = Payment::with('student.user')->whereBetween('payment_date', [$startDate, $endDate])->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('students', 'payments', 'startDate', 'endDate'));
        return $pdf->download('hisobot-' . $startDate->format('Y-m') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new StudentsExport(), 'oquvchilar-' . date('Y-m-d') . '.xlsx');
    }
}
