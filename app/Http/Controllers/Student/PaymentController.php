<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $payments = Payment::where('student_id', $student->id)
            ->latest('payment_date')->paginate(15);
        return view('student.payments.index', compact('payments', 'student'));
    }
}
