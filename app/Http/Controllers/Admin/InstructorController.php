<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        $instructors = Instructor::with('user', 'students', 'vehicle')
            ->when($request->search, fn($q) => $q->whereHas('user', fn($u) =>
                $u->where('name', 'like', '%'.$request->search.'%')
            ))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)->withQueryString();

        return view('admin.instructors.index', compact('instructors'));
    }

    public function create()
    {
        return view('admin.instructors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:8',
            'license_categories' => 'required|array',
            'hire_date' => 'required|date',
            'experience_years' => 'required|integer|min:0',
            'salary' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'status' => 'active',
                'gender' => $request->gender,
                'email_verified_at' => now(),
            ]);
            $user->assignRole('instructor');

            $lastInstructor = Instructor::latest('id')->first();
            $number = $lastInstructor ? (int)substr($lastInstructor->employee_number, 4) + 1 : 1;

            Instructor::create([
                'user_id' => $user->id,
                'employee_number' => 'INS-' . str_pad($number, 3, '0', STR_PAD_LEFT),
                'license_categories' => $request->license_categories,
                'driving_license_number' => $request->driving_license_number,
                'driving_license_expiry' => $request->driving_license_expiry,
                'experience_years' => $request->experience_years,
                'status' => 'active',
                'hire_date' => $request->hire_date,
                'salary' => $request->salary,
                'max_students' => $request->max_students ?? 15,
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('admin.instructors.index')
            ->with('success', "Instruktor muvaffaqiyatli qo'shildi!");
    }

    public function show(Instructor $instructor)
    {
        $instructor->load('user', 'students.user', 'vehicle', 'lessons');
        return view('admin.instructors.show', compact('instructor'));
    }

    public function edit(Instructor $instructor)
    {
        $instructor->load('user');
        return view('admin.instructors.edit', compact('instructor'));
    }

    public function update(Request $request, Instructor $instructor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$instructor->user_id,
            'status' => 'required|in:active,inactive,on_leave',
        ]);

        DB::transaction(function () use ($request, $instructor) {
            $instructor->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);

            $instructor->update([
                'license_categories' => $request->license_categories ?? $instructor->license_categories,
                'experience_years' => $request->experience_years,
                'status' => $request->status,
                'hire_date' => $request->hire_date,
                'salary' => $request->salary,
                'max_students' => $request->max_students,
                'driving_license_number' => $request->driving_license_number,
                'driving_license_expiry' => $request->driving_license_expiry,
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('admin.instructors.show', $instructor)
            ->with('success', "Instruktor ma'lumotlari yangilandi!");
    }

    public function destroy(Instructor $instructor)
    {
        DB::transaction(function () use ($instructor) {
            $userId = $instructor->user_id;
            $instructor->delete();
            User::destroy($userId);
        });

        return redirect()->route('admin.instructors.index')
            ->with('success', "Instruktor o'chirildi.");
    }
}
