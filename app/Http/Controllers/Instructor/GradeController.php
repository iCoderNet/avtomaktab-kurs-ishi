<?php
namespace App\Http\Controllers\Instructor;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $instructor = auth()->user()->instructor;
        $students = Student::with('user', 'grades')->where('instructor_id', $instructor->id)->get();
        return view('instructor.grades.index', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:theory,practical,behavior,final',
            'score' => 'required|integer|min:0|max:100',
        ]);

        Grade::create([
            'student_id' => $request->student_id,
            'instructor_id' => auth()->user()->instructor->id,
            'type' => $request->type,
            'score' => $request->score,
            'grade_letter' => Grade::getLetterGrade($request->score),
            'comment' => $request->comment,
            'graded_at' => now(),
        ]);

        return back()->with('success', "Baho qo'yildi!");
    }
}
