<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('user', 'instructor.user')
            ->when($request->search, fn($q) => $q->whereHas('user', fn($u) =>
                $u->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%')
            ))
            ->when($request->search, fn($q) => $q->orWhere('student_number', 'like', '%'.$request->search.'%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->category, fn($q) => $q->where('license_category', $request->category))
            ->latest();

        $students = $query->paginate(15)->withQueryString();
        $instructors = Instructor::with('user')->where('status', 'active')->get();

        return view('admin.students.index', compact('students', 'instructors'));
    }

    public function create()
    {
        $instructors = Instructor::with('user')->where('status', 'active')->get();
        return view('admin.students.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:8',
            'license_category' => 'required|in:A,B,C,D',
            'enrollment_date' => 'required|date',
            'total_fee' => 'required|numeric|min:0',
            'instructor_id' => 'nullable|exists:instructors,id',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'status' => 'active',
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
                'email_verified_at' => now(),
            ]);
            $user->assignRole('student');

            $lastStudent = Student::latest('id')->first();
            $number = $lastStudent ? (int)substr($lastStudent->student_number, 4) + 1 : 1;

            Student::create([
                'user_id' => $user->id,
                'student_number' => 'STD-' . str_pad($number, 4, '0', STR_PAD_LEFT),
                'passport_series' => $request->passport_series,
                'passport_number' => $request->passport_number,
                'license_category' => $request->license_category,
                'instructor_id' => $request->instructor_id,
                'status' => 'active',
                'enrollment_date' => $request->enrollment_date,
                'expected_graduation' => $request->expected_graduation,
                'total_fee' => $request->total_fee,
                'paid_amount' => 0,
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', "O'quvchi muvaffaqiyatli qo'shildi!");
    }

    public function show(Student $student)
    {
        $student->load('user', 'instructor.user', 'lessons.schedule', 'payments', 'grades', 'examResults.exam', 'certificates');
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        $instructors = Instructor::with('user')->where('status', 'active')->get();
        return view('admin.students.edit', compact('student', 'instructors'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$student->user_id,
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive,completed,expelled',
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
            ]);

            $student->update([
                'license_category' => $request->license_category,
                'instructor_id' => $request->instructor_id,
                'status' => $request->status,
                'enrollment_date' => $request->enrollment_date,
                'expected_graduation' => $request->expected_graduation,
                'total_fee' => $request->total_fee,
                'passport_series' => $request->passport_series,
                'passport_number' => $request->passport_number,
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('admin.students.show', $student)
            ->with('success', "O'quvchi ma'lumotlari yangilandi!");
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $userId = $student->user_id;
            $student->delete();
            User::destroy($userId);
        });

        return redirect()->route('admin.students.index')
            ->with('success', "O'quvchi o'chirildi.");
    }
}
