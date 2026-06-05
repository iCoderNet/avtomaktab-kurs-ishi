<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;
        $lessons = Lesson::with('instructor.user', 'vehicle', 'attendance')
            ->where('student_id', $student->id)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('date')->orderBy('start_time')
            ->paginate(15)->withQueryString();
        return view('student.lessons.index', compact('lessons'));
    }
}
