<?php
namespace App\Http\Controllers\Instructor;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $instructor = auth()->user()->instructor;
        $students = Student::with('user')->where('instructor_id', $instructor->id)
            ->where('status', 'active')->paginate(15);
        return view('instructor.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $instructor = auth()->user()->instructor;
        if ($student->instructor_id !== $instructor->id) abort(403);
        $student->load('user', 'lessons', 'grades', 'attendances', 'payments');
        return view('instructor.students.show', compact('student'));
    }
}
