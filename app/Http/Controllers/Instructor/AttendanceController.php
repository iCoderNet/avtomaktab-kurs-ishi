<?php
namespace App\Http\Controllers\Instructor;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $instructor = auth()->user()->instructor;
        $lessons = Lesson::with('student.user', 'attendance')
            ->where('instructor_id', $instructor->id)
            ->where('status', 'scheduled')
            ->whereDate('date', '<=', now())
            ->orderByDesc('date')->orderBy('start_time')
            ->paginate(20);
        return view('instructor.attendance.index', compact('lessons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'attendances' => 'required|array',
        ]);

        foreach ($request->attendances as $studentId => $status) {
            Attendance::updateOrCreate(
                ['lesson_id' => $request->lesson_id, 'student_id' => $studentId],
                ['status' => $status, 'marked_by' => auth()->id()]
            );
        }

        Lesson::find($request->lesson_id)->update(['status' => 'completed']);
        return back()->with('success', "Davomat belgilandi!");
    }

    public function update(Request $request, Attendance $attendance)
    {
        $attendance->update(['status' => $request->status]);
        return back()->with('success', "Davomat yangilandi!");
    }
}
