<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\Grade;

class GradeController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $grades = Grade::with('instructor.user', 'lesson')
            ->where('student_id', $student->id)
            ->orderByDesc('graded_at')->paginate(20);
        $avgScore = Grade::where('student_id', $student->id)->avg('score');
        return view('student.grades.index', compact('grades', 'avgScore'));
    }
}
