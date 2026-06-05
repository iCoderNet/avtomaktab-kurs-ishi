<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Instructor;
use App\Http\Controllers\Student;
use App\Http\Controllers\NotificationController;

// Bosh sahifa - login sahifasiga yo'naltirish
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('admin')) return redirect()->route('admin.dashboard');
        if ($user->hasRole('instructor')) return redirect()->route('instructor.dashboard');
        return redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // O'quvchilar
    Route::resource('students', Admin\StudentController::class);

    // Instruktorlar
    Route::resource('instructors', Admin\InstructorController::class);

    // Avtomobillar
    Route::resource('vehicles', Admin\VehicleController::class);

    // Dars jadvali
    Route::resource('schedules', Admin\ScheduleController::class);
    Route::post('schedules/{schedule}/assign', [Admin\ScheduleController::class, 'assignStudents'])->name('schedules.assign');

    // Imtihonlar
    Route::resource('exams', Admin\ExamController::class);
    Route::get('exams/{exam}/results', [Admin\ExamController::class, 'results'])->name('exams.results');

    // To'lovlar
    Route::resource('payments', Admin\PaymentController::class);
    Route::get('payments/receipt/{payment}', [Admin\PaymentController::class, 'receipt'])->name('payments.receipt');

    // Sertifikatlar
    Route::resource('certificates', Admin\CertificateController::class);
    Route::get('certificates/{certificate}/download', [Admin\CertificateController::class, 'download'])->name('certificates.download');

    // Guruhlar
    Route::resource('groups', Admin\GroupController::class);
    Route::get('groups/{group}/schedule-events', [Admin\GroupController::class, 'scheduleEvents'])->name('groups.schedule-events');
    Route::post('groups/{group}/generate-schedule', [Admin\GroupController::class, 'generateSchedule'])->name('groups.generate-schedule');
    Route::post('groups/{group}/add-student', [Admin\GroupController::class, 'addStudent'])->name('groups.add-student');
    Route::delete('groups/{group}/remove-student/{student}', [Admin\GroupController::class, 'removeStudent'])->name('groups.remove-student');

    // Hisobotlar
    Route::get('reports', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/pdf', [Admin\ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('reports/export/excel', [Admin\ReportController::class, 'exportExcel'])->name('reports.export.excel');
});

// ============================================================
// INSTRUKTOR ROUTES
// ============================================================
Route::middleware(['auth', 'verified', 'role:instructor'])->prefix('instruktor')->name('instructor.')->group(function () {

    Route::get('/dashboard', [Instructor\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/students', [Instructor\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [Instructor\StudentController::class, 'show'])->name('students.show');

    Route::get('/schedule', [Instructor\ScheduleController::class, 'index'])->name('schedule.index');

    Route::get('/attendance', [Instructor\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [Instructor\AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('/attendance/{attendance}', [Instructor\AttendanceController::class, 'update'])->name('attendance.update');

    Route::get('/grades', [Instructor\GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades', [Instructor\GradeController::class, 'store'])->name('grades.store');
});

// ============================================================
// O'QUVCHI ROUTES
// ============================================================
Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {

    Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/lessons', [Student\LessonController::class, 'index'])->name('lessons.index');

    Route::get('/exams', [Student\ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/{exam}/take', [Student\ExamController::class, 'take'])->name('exams.take');
    Route::post('/exams/{exam}/submit', [Student\ExamController::class, 'submit'])->name('exams.submit');

    Route::get('/grades', [Student\GradeController::class, 'index'])->name('grades.index');

    Route::get('/payments', [Student\PaymentController::class, 'index'])->name('payments.index');

    Route::get('/certificates', [Student\CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}/download', [Student\CertificateController::class, 'download'])->name('certificates.download');
});

// Bildirishnomalar (barcha rollar uchun)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
});
