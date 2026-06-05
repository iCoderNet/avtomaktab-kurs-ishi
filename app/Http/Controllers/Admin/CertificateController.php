<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with('student.user', 'issuedBy')->latest()->paginate(15);
        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        $students = Student::with('user')->where('status', 'active')->orWhere('status', 'completed')->get();
        return view('admin.certificates.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate(['student_id' => 'required|exists:students,id']);

        $last = Certificate::latest('id')->first();
        $number = 'CERT-' . date('Y') . '-' . str_pad(($last ? (int)substr($last->certificate_number, -4) + 1 : 1), 4, '0', STR_PAD_LEFT);

        $cert = Certificate::create([
            'student_id' => $request->student_id,
            'certificate_number' => $number,
            'license_category' => $request->license_category ?? 'B',
            'issued_date' => $request->issued_date ?? now(),
            'expiry_date' => $request->expiry_date,
            'status' => 'active',
            'issued_by' => auth()->id(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.certificates.index')
            ->with('success', "Sertifikat #{$number} yaratildi!");
    }

    public function show(Certificate $certificate)
    {
        $certificate->load('student.user', 'issuedBy');
        return view('admin.certificates.show', compact('certificate'));
    }
    public function edit(Certificate $certificate) {}
    public function update(Request $request, Certificate $certificate)
    {
        $certificate->update($request->except(['_token','_method']));
        return redirect()->route('admin.certificates.index')->with('success', "Yangilandi!");
    }
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return redirect()->route('admin.certificates.index')->with('success', "O'chirildi.");
    }

    public function download(Certificate $certificate)
    {
        $certificate->load('student.user', 'issuedBy');
        $pdf = Pdf::loadView('admin.certificates.pdf', compact('certificate'))
            ->setPaper('A4', 'landscape');
        return $pdf->download("sertifikat-{$certificate->certificate_number}.pdf");
    }
}
