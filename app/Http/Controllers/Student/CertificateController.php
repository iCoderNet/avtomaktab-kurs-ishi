<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $certificates = Certificate::where('student_id', $student->id)->latest()->get();
        return view('student.certificates.index', compact('certificates'));
    }

    public function download(Certificate $certificate)
    {
        $student = auth()->user()->student;
        if ($certificate->student_id !== $student->id) abort(403);
        $certificate->load('student.user');
        $pdf = Pdf::loadView('admin.certificates.pdf', compact('certificate'))
            ->setPaper('A4', 'landscape');
        return $pdf->download("sertifikat-{$certificate->certificate_number}.pdf");
    }
}
