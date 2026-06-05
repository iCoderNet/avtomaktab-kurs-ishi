<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('instructor.user')
            ->withCount('students')
            ->withCount('schedules')
            ->latest()
            ->paginate(10);

        return view('admin.groups.index', compact('groups'));
    }

    public function create()
    {
        $instructors = Instructor::with('user')->where('status', 'active')->get();
        $students = Student::with('user')
            ->whereNull('group_id')
            ->where('status', 'active')
            ->orderBy('id')
            ->get();

        return view('admin.groups.create', compact('instructors', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'instructor_id' => 'required|exists:instructors,id',
            'students'      => 'required|array|min:1|max:30',
            'students.*'    => 'exists:students,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
        ], [
            'name.required'          => 'Guruh nomi kiritilishi shart.',
            'instructor_id.required' => 'Instruktor tanlanishi shart.',
            'students.required'      => 'Kamida 1 ta o\'quvchi tanlanishi kerak.',
            'start_date.required'    => 'Boshlanish sanasi kiritilishi shart.',
            'end_date.required'      => 'Tugash sanasi kiritilishi shart.',
            'end_date.after'         => 'Tugash sanasi boshlanishdan keyin bo\'lishi kerak.',
        ]);

        try {
            DB::beginTransaction();

            $group = Group::create([
                'name'          => $request->name,
                'instructor_id' => $request->instructor_id,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'status'        => 'active',
            ]);

            Student::whereIn('id', $request->students)->update([
                'group_id'      => $group->id,
                'instructor_id' => $request->instructor_id,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.groups.show', $group->id)
                ->with('success', 'Guruh muvaffaqiyatli yaratildi. Endi dars jadvalini generatsiya qiling.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Xatolik yuz berdi: ' . $e->getMessage());
        }
    }

    public function show(Group $group)
    {
        $group->load(['instructor.user', 'students.user']);

        $scheduleIds    = $group->schedules()->pluck('id');
        $schedulesCount = $scheduleIds->count();
        $lessonsCount   = Lesson::whereIn('schedule_id', $scheduleIds)->count();

        $completedLessons = Lesson::whereIn('schedule_id', $scheduleIds)
            ->where('status', 'completed')
            ->count();

        $progressPercent = $schedulesCount > 0
            ? round(($completedLessons / max($lessonsCount, 1)) * 100)
            : 0;

        // Tur bo'yicha jadval soni
        $theoryCount         = $group->schedules()->where('lesson_type', 'theory')->count();
        $practicalCount      = $group->schedules()->where('lesson_type', 'practical')->count();
        $theoryFutureCount   = $group->schedules()->where('lesson_type', 'theory')->where('date', '>', today())->count();
        $practicalFutureCount = $group->schedules()->where('lesson_type', 'practical')->where('date', '>', today())->count();

        // O'quvchi qo'shish uchun mavjud bo'sh o'quvchilar
        $availableStudents = Student::with('user')
            ->whereNull('group_id')
            ->where('status', 'active')
            ->orderBy('id')
            ->get();

        return view('admin.groups.show', compact(
            'group',
            'schedulesCount',
            'lessonsCount',
            'completedLessons',
            'progressPercent',
            'theoryCount',
            'practicalCount',
            'theoryFutureCount',
            'practicalFutureCount',
            'availableStudents'
        ));
    }

    public function edit(Group $group)
    {
        $instructors = Instructor::with('user')->where('status', 'active')->get();

        return view('admin.groups.edit', compact('group', 'instructors'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'instructor_id' => 'required|exists:instructors,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'status'        => 'required|in:active,completed,cancelled',
        ], [
            'end_date.after' => 'Tugash sanasi boshlanishdan keyin bo\'lishi kerak.',
        ]);

        try {
            DB::beginTransaction();

            $oldInstructorId = $group->instructor_id;

            $group->update([
                'name'          => $request->name,
                'instructor_id' => $request->instructor_id,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'status'        => $request->status,
            ]);

            // Instruktor o'zgargan bo'lsa, bog'liq yozuvlarni yangilash
            if ($oldInstructorId != $request->instructor_id) {
                $group->students()->update(['instructor_id' => $request->instructor_id]);
                $group->schedules()->update(['instructor_id' => $request->instructor_id]);

                $scheduleIds = $group->schedules()->pluck('id');
                Lesson::whereIn('schedule_id', $scheduleIds)
                    ->update(['instructor_id' => $request->instructor_id]);
            }

            DB::commit();

            return redirect()
                ->route('admin.groups.show', $group->id)
                ->with('success', 'Guruh ma\'lumotlari yangilandi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Xatolik: ' . $e->getMessage());
        }
    }

    public function destroy(Group $group)
    {
        try {
            DB::beginTransaction();

            // O'quvchilarni guruhdan ozod qilish
            $group->students()->update(['group_id' => null]);

            // Guruh darslarini o'chirish
            $scheduleIds = $group->schedules()->pluck('id');
            Lesson::whereIn('schedule_id', $scheduleIds)->delete();

            // Jadvallarni o'chirish
            $group->schedules()->delete();

            // Guruhni o'chirish
            $group->delete();

            DB::commit();

            return redirect()
                ->route('admin.groups.index')
                ->with('success', 'Guruh va barcha bog\'liq ma\'lumotlar o\'chirildi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Xatolik: ' . $e->getMessage());
        }
    }

    public function generateSchedule(Request $request, Group $group)
    {
        $request->validate([
            'days_of_week'   => 'required|array|min:1',
            'days_of_week.*' => 'integer|min:1|max:7',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i',
            'lesson_type'    => 'required|in:theory,practical',
        ], [
            'days_of_week.required' => 'Kamida 1 ta kun tanlang.',
            'end_time.date_format'  => 'Tugash vaqti to\'g\'ri formatda bo\'lishi kerak.',
        ]);

        // Vaqtni tekshirish
        $startTime = Carbon::parse($request->start_time);
        $endTime   = Carbon::parse($request->end_time);

        if ($endTime->lte($startTime)) {
            return back()->withInput()->with('error', 'Tugash vaqti boshlanish vaqtidan keyin bo\'lishi kerak.');
        }

        try {
            DB::beginTransaction();

            $today       = today();
            $lessonType  = $request->lesson_type;

            // Faqat shu turdagi KELAJAKDAGI jadvallarni o'chirish (o'tgan darslar saqlanib qoladi)
            $futureScheduleIds = $group->schedules()
                ->where('lesson_type', $lessonType)
                ->where('date', '>', $today)
                ->pluck('id');

            if ($futureScheduleIds->isNotEmpty()) {
                Lesson::whereIn('schedule_id', $futureScheduleIds)->delete();
                Schedule::whereIn('id', $futureScheduleIds)->delete();
            }

            // O'tgan/bugungi shu turdagi mavjud sanalarni olish (qayta generatsiyada duplikat bo'lmasin)
            $existingDates = $group->schedules()
                ->where('lesson_type', $lessonType)
                ->where('date', '<=', $today)
                ->pluck('date')
                ->map(fn($d) => $d->format('Y-m-d'))
                ->toArray();

            $startDate       = Carbon::parse($group->start_date);
            $endDate         = Carbon::parse($group->end_date);
            $daysOfWeek      = array_map('intval', $request->days_of_week);
            $durationMinutes = $endTime->diffInMinutes($startTime);

            $currentDate   = $startDate->copy();
            $schedulesData = [];

            while ($currentDate->lte($endDate)) {
                if (in_array($currentDate->dayOfWeekIso, $daysOfWeek)) {
                    $dateStr = $currentDate->format('Y-m-d');
                    // O'tib ketgan va allaqachon mavjud sanalarga tegmaymiz
                    if (!in_array($dateStr, $existingDates)) {
                        $schedulesData[] = [
                            'group_id'          => $group->id,
                            'instructor_id'     => $group->instructor_id,
                            'lesson_type'       => $lessonType,
                            'date'              => $dateStr,
                            'start_time'        => $request->start_time,
                            'end_time'          => $request->end_time,
                            'max_students'      => $group->students()->count(),
                            'status'            => 'scheduled',
                            'is_recurring'      => true,
                            'recurring_pattern' => 'weekly',
                            'created_at'        => now(),
                            'updated_at'        => now(),
                        ];
                    }
                }
                $currentDate->addDay();
            }

            if (empty($schedulesData)) {
                DB::rollBack();
                return back()->with('error', 'Guruh muddati ichida tanlangan kunlarda hech qanday yangi dars topilmadi. Sanalar va kunlarni tekshiring.');
            }

            // Jadvallarni saqlash
            Schedule::insert($schedulesData);

            // Yangi saqlangan jadvallarni olish (faqat hozir qo'shilganlar)
            $newDates = array_column($schedulesData, 'date');
            $insertedSchedules = Schedule::where('group_id', $group->id)
                ->where('lesson_type', $lessonType)
                ->whereIn('date', $newDates)
                ->get(['id', 'date', 'lesson_type']);

            // Har bir o'quvchi uchun Lesson yozuvlarini yaratish
            $studentIds  = $group->students()->pluck('id');
            $lessonsData = [];

            foreach ($insertedSchedules as $schedule) {
                foreach ($studentIds as $studentId) {
                    $lessonsData[] = [
                        'schedule_id'      => $schedule->id,
                        'student_id'       => $studentId,
                        'instructor_id'    => $group->instructor_id,
                        'type'             => $lessonType,
                        'date'             => $schedule->date->format('Y-m-d'),
                        'start_time'       => $request->start_time,
                        'end_time'         => $request->end_time,
                        'status'           => 'scheduled',
                        'duration_minutes' => $durationMinutes,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ];
                }
            }

            // Katta hajmdagi ma'lumotlarni bo'lib-bo'lib saqlash
            foreach (array_chunk($lessonsData, 500) as $chunk) {
                Lesson::insert($chunk);
            }

            DB::commit();

            $totalSchedules = count($schedulesData);
            $totalLessons   = count($lessonsData);
            $typeLabel      = $lessonType === 'theory' ? 'nazariy' : 'amaliy';

            return redirect()
                ->route('admin.groups.show', $group->id)
                ->with('success', "Jadval yangilandi: {$totalSchedules} ta yangi {$typeLabel} dars, {$totalLessons} ta o'quvchi dars yozuvi. O'tgan darslar saqlanib qoldi.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Xatolik: ' . $e->getMessage());
        }
    }

    public function scheduleEvents(Group $group)
    {
        $schedules = Schedule::where('group_id', $group->id)->get();

        $typeConfig = [
            'theory'    => ['bg' => '#6366f1', 'border' => '#4f46e5', 'label' => 'Nazariy'],
            'practical' => ['bg' => '#10b981', 'border' => '#059669', 'label' => 'Amaliy'],
        ];

        $events = $schedules->map(function ($schedule) use ($group, $typeConfig) {
            $cfg = $typeConfig[$schedule->lesson_type] ?? $typeConfig['theory'];

            return [
                'id'              => $schedule->id,
                'title'           => $cfg['label'],
                'start'           => $schedule->date->format('Y-m-d') . 'T' . $schedule->start_time->format('H:i:s'),
                'end'             => $schedule->date->format('Y-m-d') . 'T' . $schedule->end_time->format('H:i:s'),
                'backgroundColor' => $cfg['bg'],
                'borderColor'     => $cfg['border'],
                'extendedProps'   => [
                    'type'  => $schedule->lesson_type,
                    'group' => $group->name,
                ],
            ];
        });

        return response()->json($events);
    }

    public function addStudent(Request $request, Group $group)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::with('user')->findOrFail($request->student_id);

        if ($student->group_id) {
            return back()->with('error', 'Bu o\'quvchi allaqachon boshqa guruhga biriktirilgan.');
        }

        try {
            DB::beginTransaction();

            $student->update([
                'group_id'      => $group->id,
                'instructor_id' => $group->instructor_id,
            ]);

            // Kelgusi darslar uchun Lesson yozuvlarini yaratish
            $futureSchedules = $group->schedules()
                ->where('date', '>=', today())
                ->where('status', 'scheduled')
                ->get(['id', 'date', 'lesson_type', 'start_time', 'end_time', 'instructor_id']);

            if ($futureSchedules->isNotEmpty()) {
                $lessonsData = $futureSchedules->map(fn($s) => [
                    'schedule_id'      => $s->id,
                    'student_id'       => $student->id,
                    'instructor_id'    => $group->instructor_id,
                    'type'             => $s->lesson_type,
                    'date'             => $s->date->format('Y-m-d'),
                    'start_time'       => $s->start_time->format('H:i'),
                    'end_time'         => $s->end_time->format('H:i'),
                    'status'           => 'scheduled',
                    'duration_minutes' => $s->start_time->diffInMinutes($s->end_time),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ])->toArray();

                Lesson::insert($lessonsData);
            }

            DB::commit();

            return back()->with('success', $student->user->name . ' guruhga muvaffaqiyatli qo\'shildi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Xatolik: ' . $e->getMessage());
        }
    }

    public function removeStudent(Group $group, Student $student)
    {
        if ($student->group_id !== $group->id) {
            return back()->with('error', 'Bu o\'quvchi ushbu guruhga tegishli emas.');
        }

        try {
            DB::beginTransaction();

            // Kelgusi rejalashtirilgan darslarni o'chirish
            $futureScheduleIds = $group->schedules()
                ->where('date', '>=', today())
                ->pluck('id');

            if ($futureScheduleIds->isNotEmpty()) {
                Lesson::where('student_id', $student->id)
                    ->whereIn('schedule_id', $futureScheduleIds)
                    ->where('status', 'scheduled')
                    ->delete();
            }

            $student->update(['group_id' => null]);

            DB::commit();

            return back()->with('success', $student->user->name . ' guruhdan chiqarildi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Xatolik: ' . $e->getMessage());
        }
    }
}
