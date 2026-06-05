<?php
namespace App\Http\Controllers\Instructor;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $instructor = auth()->user()->instructor;
        $week = $request->week ? Carbon::parse($request->week)->startOfWeek() : Carbon::now()->startOfWeek();

        $lessons = Lesson::with('student.user', 'vehicle', 'schedule')
            ->where('instructor_id', $instructor->id)
            ->whereBetween('date', [$week, $week->copy()->endOfWeek()])
            ->orderBy('date')->orderBy('start_time')
            ->get()->groupBy(fn($l) => $l->date->format('Y-m-d'));

        return view('instructor.schedule.index', compact('lessons', 'week'));
    }
}
