<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Lesson;
use App\Models\Instructor;
use App\Models\Vehicle;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        $schedules = Schedule::with('instructor.user', 'vehicle', 'lessons.student.user')
            ->whereDate('date', $date)
            ->orderBy('start_time')->get();
        $instructors = Instructor::with('user')->where('status', 'active')->get();
        $vehicles = Vehicle::where('status', 'available')->get();
        return view('admin.schedules.index', compact('schedules', 'date', 'instructors', 'vehicles'));
    }

    public function create()
    {
        $instructors = Instructor::with('user')->where('status', 'active')->get();
        $vehicles = Vehicle::whereIn('status', ['available', 'in_use'])->get();
        return view('admin.schedules.create', compact('instructors', 'vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'lesson_type' => 'required|in:theory,practical',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Jadval to'qnashuvi tekshirish
        $conflict = Schedule::where('instructor_id', $request->instructor_id)
            ->whereDate('date', $request->date)
            ->where(fn($q) => $q
                ->whereBetween('start_time', [$request->start_time, $request->end_time])
                ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
            )->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Bu vaqtda instruktorda boshqa dars bor!');
        }

        Schedule::create($request->except('_token'));
        return redirect()->route('admin.schedules.index')->with('success', "Jadval qo'shildi!");
    }

    public function show(Schedule $schedule)
    {
        $schedule->load('instructor.user', 'vehicle', 'lessons.student.user');
        $students = Student::with('user')
            ->where('instructor_id', $schedule->instructor_id)
            ->where('status', 'active')->get();
        return view('admin.schedules.show', compact('schedule', 'students'));
    }

    public function edit(Schedule $schedule)
    {
        $instructors = Instructor::with('user')->where('status', 'active')->get();
        $vehicles = Vehicle::get();
        return view('admin.schedules.edit', compact('schedule', 'instructors', 'vehicles'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $schedule->update($request->except(['_token','_method']));
        return redirect()->route('admin.schedules.index')->with('success', "Jadval yangilandi!");
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', "Jadval o'chirildi.");
    }

    public function assignStudents(Request $request, Schedule $schedule)
    {
        $request->validate(['student_ids' => 'required|array']);
        foreach ($request->student_ids as $studentId) {
            Lesson::firstOrCreate([
                'schedule_id' => $schedule->id,
                'student_id' => $studentId,
            ], [
                'instructor_id' => $schedule->instructor_id,
                'vehicle_id' => $schedule->vehicle_id,
                'type' => $schedule->lesson_type,
                'date' => $schedule->date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'status' => 'scheduled',
                'duration_minutes' => 45,
            ]);
        }
        return back()->with('success', "O'quvchilar darsga qo'shildi!");
    }
}
