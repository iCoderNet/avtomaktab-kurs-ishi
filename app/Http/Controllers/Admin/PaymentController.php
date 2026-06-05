<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with('student.user', 'receivedBy')
            ->when($request->search, fn($q) => $q->whereHas('student.user', fn($u) =>
                $u->where('name', 'like', '%'.$request->search.'%')
            )->orWhere('receipt_number', 'like', '%'.$request->search.'%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->month, fn($q) => $q->whereMonth('payment_date', Carbon::parse($request->month)->month)
                ->whereYear('payment_date', Carbon::parse($request->month)->year))
            ->latest('payment_date')->paginate(20)->withQueryString();

        $totalThisMonth = Payment::whereMonth('payment_date', now()->month)
            ->where('status', 'completed')->sum('amount');
        $debtStudents = Student::whereRaw('total_fee > paid_amount')->where('status', 'active')->count();

        return view('admin.payments.index', compact('payments', 'totalThisMonth', 'debtStudents'));
    }

    public function create(Request $request)
    {
        $students = Student::with('user')->where('status', 'active')->get();
        $selectedStudent = $request->student_id ? Student::with('user')->find($request->student_id) : null;
        return view('admin.payments.create', compact('students', 'selectedStudent'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:tuition,exam_fee,certificate,penalty,other',
            'payment_method' => 'required|in:cash,card,transfer,online',
            'payment_date' => 'required|date',
        ]);

        $lastPayment = Payment::latest('id')->first();
        $receiptNum = 'RCP-' . date('Y') . '-' . str_pad(($lastPayment ? (int)substr($lastPayment->receipt_number, -5) + 1 : 1), 5, '0', STR_PAD_LEFT);

        $payment = Payment::create([
            ...$request->except('_token'),
            'receipt_number' => $receiptNum,
            'status' => 'completed',
            'received_by' => auth()->id(),
        ]);

        // O'quvchining to'langan miqdorini yangilash
        $student = Student::find($request->student_id);
        $student->increment('paid_amount', $request->amount);

        return redirect()->route('admin.payments.receipt', $payment)
            ->with('success', "To'lov qabul qilindi! Kvitansiya: {$receiptNum}");
    }

    public function show(Payment $payment)
    {
        $payment->load('student.user', 'receivedBy');
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment) { return view('admin.payments.edit', compact('payment')); }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->except(['_token','_method']));
        return redirect()->route('admin.payments.index')->with('success', "To'lov yangilandi!");
    }

    public function destroy(Payment $payment)
    {
        // O'quvchining to'langan miqdorini kamaytirish
        $payment->student->decrement('paid_amount', $payment->amount);
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', "To'lov o'chirildi.");
    }

    public function receipt(Payment $payment)
    {
        $payment->load('student.user', 'receivedBy');
        $pdf = Pdf::loadView('admin.payments.receipt-pdf', compact('payment'));
        return $pdf->download("kvitansiya-{$payment->receipt_number}.pdf");
    }
}
