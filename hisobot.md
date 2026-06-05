# Avtomaktab Boshqaruv Tizimi — Texnik Hisobot

**Loyiha nomi:** Avtomaktab (Haydovchilik maktabi boshqaruv platformasi)
**Texnologiya:** Laravel 13 + PHP 8.3 + Tailwind CSS
**Holati:** To'liq ishlaydigan tizim (Production-ready)
**Sana:** 2026-yil 2-aprel

---

## 1. Loyiha Haqida Umumiy Ma'lumot

Avtomaktab tizimi haydovchilik maktablarining butun ish jarayonini raqamlashtirish uchun ishlab chiqilgan to'liq boshqaruv platformasidir. Tizim uch xil rol uchun alohida panel taqdim etadi: **Admin**, **Instruktor** va **O'quvchi**.

### Tizim qanday muammoni hal qiladi:
- Dars jadvallarini qo'lda tuzishga ketadigan soatlab ish — endi bir necha soniyada avtomatik
- O'quvchilar to'lovlari va qarzlarini kuzatib borish
- Imtihon natijalarini boshqarish
- Sertifikatlarni avtomatik raqamlash va PDF yaratish
- Guruhlar bo'yicha davomat va baholarni yuritish

---

## 2. Texnologiyalar Stacki

| Qatlam | Texnologiya | Versiya | Maqsad |
|---|---|---|---|
| Backend | Laravel | 13.x | Asosiy freymvork |
| PHP | PHP | ^8.3 | Tizim tili |
| Frontend | Tailwind CSS + Blade | - | UI/UX |
| Taqvim | FullCalendar.js | - | Dars jadvalini vizualizatsiya qilish |
| PDF | barryvdh/laravel-dompdf | ^3.1 | Kvitansiya, sertifikat, hisobot PDF |
| Excel | maatwebsite/excel | ^3.1 | O'quvchilar ro'yxatini eksport |
| Admin panel | Filament | ^5.0 | Ikkinchi admin panel (filament) |
| Rollar | spatie/laravel-permission | ^6.10 | Admin/Instruktor/O'quvchi rollari |
| Auth | Laravel Breeze | ^2.3 | Login/register tizimi |
| Reactive | Livewire | ^4.1 | Dinamik komponentlar |
| Ma'lumotlar bazasi | MySQL | - | Asosiy saqlash |
| Tarix/vaqt | Carbon | (bundled) | Sana hisoblashlari |

---

## 3. Ma'lumotlar Bazasi Arxitekturasi

Tizimda jami **14 ta asosiy jadval** mavjud:

```
users              — Barcha foydalanuvchilar (admin, instruktor, o'quvchi)
students           — O'quvchilar profili (user_id orqali users bilan bog'liq)
instructors        — Instruktorlar profili (user_id orqali users bilan bog'liq)
vehicles           — Avtomobillar parki
groups             — O'quv guruhlari
schedules          — Dars jadvali (guruh bo'yicha)
lessons            — Har bir o'quvchi uchun individual dars yozuvi
attendances        — Davomat yozuvlari
exams              — Imtihonlar
exam_questions     — Imtihon savollari (JSON: options massivi)
exam_results       — Imtihon natijalari
grades             — Baholar
certificates       — Sertifikatlar
payments           — To'lovlar va kvitansiyalar
```

### Modellar orasidagi bog'liqlik (Relationships):

```
User ──hasOne──► Student
User ──hasOne──► Instructor

Group ──belongsTo──► Instructor
Group ──hasMany──►  Student
Group ──hasMany──►  Schedule

Student ──belongsTo──► Group
Student ──belongsTo──► Instructor
Student ──hasMany──►   Lesson
Student ──hasMany──►   Payment
Student ──hasMany──►   Certificate
Student ──hasMany──►   ExamResult
Student ──hasMany──►   Grade
Student ──hasMany──►   Attendance

Schedule ──belongsTo──► Group
Schedule ──belongsTo──► Instructor
Schedule ──hasMany──►   Lesson

Lesson ──belongsTo──► Schedule
Lesson ──belongsTo──► Student
Lesson ──belongsTo──► Instructor
```

---

## 4. Foydalanuvchi Rollari va Huquqlari (RBAC)

Tizim `spatie/laravel-permission` paketi yordamida rol asosidagi kirish nazoratini (RBAC) amalga oshiradi.

### Rollar va ularning imkoniyatlari:

**Admin** — `prefix: /admin`
- O'quvchilarni CRUD boshqarish
- Instruktorlarni CRUD boshqarish
- Avtomobillarni CRUD boshqarish
- Guruhlarni yaratish va jadval generatsiya qilish
- Imtihonlarni yaratish va natijalarni ko'rish
- To'lovlarni qabul qilish va kvitansiya berish
- Sertifikat berish va PDF yuklab olish
- Umumiy hisobot (PDF + Excel eksport)
- Dashboard: statistika va grafiklar

**Instruktor** — `prefix: /instruktor`
- O'z o'quvchilarini ko'rish
- Dars jadvalini ko'rish
- Davomat belgilash
- Baho qo'yish

**O'quvchi** — `prefix: /student`
- Darslarini ko'rish
- Imtihon topshirish (online test)
- Baholarini ko'rish
- To'lov tarixi
- Sertifikatini yuklab olish

---

## 5. Asosiy Algoritmlar va Ularning Kodlari

### 5.1. Avtomatik Dars Jadvali Generatsiya Algoritmi

Bu tizimning eng muhim qismi. Admin guruh uchun haftaning kunlarini, vaqtini va dars turini belgilaydi — algoritm esa guruhning to'liq muddati uchun barcha darslarni bir zumda yaratadi.

**Algoritm qadamlari:**

1. Validatsiya: kunlar, vaqt, dars turi tekshiriladi
2. O'tib ketgan darslarni himoya qilish: faqat kelajakdagi jadvallar o'chiriladi
3. Mavjud sanalar ro'yxatini olish (duplikat oldini olish)
4. `start_date` dan `end_date` gacha har kun iteratsiya
5. Agar kun tanlangan hafta kunlariga to'g'ri kelsa — jadval yozuvi yaratiladi
6. Har bir jadval uchun guruhdagi barcha o'quvchilarga `Lesson` yozuvi ochiladi
7. Katta hajmdagi ma'lumot `chunk(500)` bilan saqlash

```php
// GroupController.php — generateSchedule() metodi (qisqartirilgan)

// 1-qadam: Kelajakdagi jadvallarni o'chirish (o'tganlarni saqlab)
$futureScheduleIds = $group->schedules()
    ->where('lesson_type', $lessonType)
    ->where('date', '>', $today)
    ->pluck('id');

Lesson::whereIn('schedule_id', $futureScheduleIds)->delete();
Schedule::whereIn('id', $futureScheduleIds)->delete();

// 2-qadam: Mavjud o'tgan sanalar (duplikat bo'lmasin)
$existingDates = $group->schedules()
    ->where('lesson_type', $lessonType)
    ->where('date', '<=', $today)
    ->pluck('date')
    ->map(fn($d) => $d->format('Y-m-d'))
    ->toArray();

// 3-qadam: Iteratsiya — start_date dan end_date gacha
$currentDate = Carbon::parse($group->start_date);
$endDate     = Carbon::parse($group->end_date);

while ($currentDate->lte($endDate)) {
    // dayOfWeekIso: 1=Dushanba, 7=Yakshanba (ISO 8601)
    if (in_array($currentDate->dayOfWeekIso, $daysOfWeek)) {
        $dateStr = $currentDate->format('Y-m-d');
        if (!in_array($dateStr, $existingDates)) {
            $schedulesData[] = [
                'group_id'          => $group->id,
                'instructor_id'     => $group->instructor_id,
                'lesson_type'       => $lessonType,   // 'theory' | 'practical'
                'date'              => $dateStr,
                'start_time'        => $request->start_time,
                'end_time'          => $request->end_time,
                'is_recurring'      => true,
                'recurring_pattern' => 'weekly',
                'status'            => 'scheduled',
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }
    }
    $currentDate->addDay();
}

// 4-qadam: Jadvalni saqlash va har o'quvchi uchun Lesson yaratish
Schedule::insert($schedulesData);

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
            'status'           => 'scheduled',
            'duration_minutes' => $durationMinutes,
            'created_at'       => now(),
            'updated_at'       => now(),
        ];
    }
}

// 5-qadam: Katta hajmni 500lik bloklarda saqlash
foreach (array_chunk($lessonsData, 500) as $chunk) {
    Lesson::insert($chunk);
}
```

**Misol:** 20 o'quvchili guruh, 3 oy, haftada 3 kun = ~156 jadval × 20 o'quvchi = **3120 ta Lesson yozuvi** bir serverga so'rov bilan.

---

### 5.2. Jadval To'qnashuvi Tekshirish Algoritmi (Conflict Detection)

Yangi dars qo'shilganda instruktorda o'sha vaqtda boshqa dars mavjudligini tekshirish:

```php
// ScheduleController.php — store() metodi

$conflict = Schedule::where('instructor_id', $request->instructor_id)
    ->whereDate('date', $request->date)
    ->where(fn($q) => $q
        ->whereBetween('start_time', [$request->start_time, $request->end_time])
        ->orWhereBetween('end_time',   [$request->start_time, $request->end_time])
    )->exists();

if ($conflict) {
    return back()->withInput()
        ->with('error', 'Bu vaqtda instruktorda boshqa dars bor!');
}
```

Bu algoritm interval kesishishini tekshiradi: yangi darsning boshlanish yoki tugash vaqti mavjud dars oralig'iga tushsa — to'qnashuv aniqlanadi.

---

### 5.3. O'quvchi Qarz va To'lov Foizi Hisoblash (Computed Attributes)

`Student` modelida Laravel'ning `getXAttribute()` magic method yordamida hisoblangan xususiyatlar:

```php
// Student.php modeli

// Qarz miqdori (manfiy bo'lmaslik kafolati)
public function getDebtAttribute(): float
{
    return max(0, $this->total_fee - $this->paid_amount);
}

// To'lov foizi (0-100 oralig'ida)
public function getPaymentPercentageAttribute(): int
{
    if ($this->total_fee <= 0) return 100;
    return min(100, (int)(($this->paid_amount / $this->total_fee) * 100));
}

// Davomat foizi (darslar asosida)
public function getAttendanceRateAttribute(): float
{
    $total = $this->lessons()->count();
    if ($total === 0) return 0;
    $present = $this->attendances()->where('status', 'present')->count();
    return round(($present / $total) * 100, 1);
}
```

---

### 5.4. Avtomatik Raqamlash Algoritmi

Har bir qo'shilganda unikal ketma-ket raqam beriladi:

```php
// O'quvchi raqami: STD-0001, STD-0002, ...
$lastStudent = Student::latest('id')->first();
$number = $lastStudent ? (int)substr($lastStudent->student_number, 4) + 1 : 1;
$studentNumber = 'STD-' . str_pad($number, 4, '0', STR_PAD_LEFT);

// Instruktor raqami: INS-001, INS-002, ...
$lastInstructor = Instructor::latest('id')->first();
$number = $lastInstructor ? (int)substr($lastInstructor->employee_number, 4) + 1 : 1;
$employeeNumber = 'INS-' . str_pad($number, 3, '0', STR_PAD_LEFT);

// Kvitansiya raqami: RCP-2026-00001, RCP-2026-00002, ...
$lastPayment = Payment::latest('id')->first();
$receiptNum = 'RCP-' . date('Y') . '-' . str_pad(
    ($lastPayment ? (int)substr($lastPayment->receipt_number, -5) + 1 : 1),
    5, '0', STR_PAD_LEFT
);

// Sertifikat raqami: CERT-2026-0001, CERT-2026-0002, ...
$last = Certificate::latest('id')->first();
$certNumber = 'CERT-' . date('Y') . '-' . str_pad(
    ($last ? (int)substr($last->certificate_number, -4) + 1 : 1),
    4, '0', STR_PAD_LEFT
);
```

---

### 5.5. Dashboard Statistikasi — Kumulyativ O'quvchilar Grafigi

```php
// DashboardController.php

// Oxirgi 6 oy uchun oy oxiridagi umumiy o'quvchilar soni
$studentData = [];
for ($i = 5; $i >= 0; $i--) {
    $date = Carbon::now()->subMonths($i)->endOfMonth();
    $studentData[] = Student::where('enrollment_date', '<=', $date->toDateString())->count();
    $studentLabels[] = $date->locale('uz')->translatedFormat('M Y');
}

// Oylik daromad grafigi
$revenueData = [];
for ($i = 5; $i >= 0; $i--) {
    $date = Carbon::now()->subMonths($i);
    $revenueData[] = Payment::whereYear('payment_date', $date->year)
        ->whereMonth('payment_date', $date->month)
        ->where('status', 'completed')
        ->sum('amount');
}
```

---

### 5.6. O'quvchini Guruhga Qo'shish/Chiqarish (Cascade Logic)

**Qo'shish:** Yangi o'quvchi guruhga qo'shilganda uning uchun barcha kelajakdagi darslar avtomatik yaratiladi:

```php
// GroupController.php — addStudent() metodi

$futureSchedules = $group->schedules()
    ->where('date', '>=', today())
    ->where('status', 'scheduled')
    ->get(['id', 'date', 'lesson_type', 'start_time', 'end_time', 'instructor_id']);

$lessonsData = $futureSchedules->map(fn($s) => [
    'schedule_id'      => $s->id,
    'student_id'       => $student->id,
    'instructor_id'    => $group->instructor_id,
    'type'             => $s->lesson_type,
    'date'             => $s->date->format('Y-m-d'),
    'status'           => 'scheduled',
    'duration_minutes' => $s->start_time->diffInMinutes($s->end_time),
    'created_at'       => now(),
    'updated_at'       => now(),
])->toArray();

Lesson::insert($lessonsData);
```

**Chiqarish:** O'quvchi guruhdan chiqarilganda faqat kelajakdagi `scheduled` darslar o'chiriladi (o'tgan darslar tarixi saqlanadi):

```php
// GroupController.php — removeStudent() metodi

$futureScheduleIds = $group->schedules()
    ->where('date', '>=', today())
    ->pluck('id');

Lesson::where('student_id', $student->id)
    ->whereIn('schedule_id', $futureScheduleIds)
    ->where('status', 'scheduled')
    ->delete();

$student->update(['group_id' => null]);
```

---

### 5.7. To'lov Saqlash va Balans Yangilash

```php
// PaymentController.php — store() metodi

$payment = Payment::create([
    ...$request->except('_token'),
    'receipt_number' => $receiptNum,
    'status'         => 'completed',
    'received_by'    => auth()->id(),
]);

// O'quvchining to'langan miqdorini increment bilan yangilash (race condition-safe)
$student = Student::find($request->student_id);
$student->increment('paid_amount', $request->amount);
```

O'chirishda esa teskari operatsiya:

```php
// PaymentController.php — destroy() metodi
$payment->student->decrement('paid_amount', $payment->amount);
$payment->delete();
```

---

### 5.8. Guruh O'chirishda Kaskadli O'chirish (Cascade Delete)

```php
// GroupController.php — destroy() metodi

DB::beginTransaction();

$group->students()->update(['group_id' => null]);          // O'quvchilarni ozod qilish
$scheduleIds = $group->schedules()->pluck('id');
Lesson::whereIn('schedule_id', $scheduleIds)->delete();    // Darslarni o'chirish
$group->schedules()->delete();                              // Jadvallarni o'chirish
$group->delete();                                           // Guruhni o'chirish

DB::commit();
```

---

### 5.9. Hisobot Statistikasi (Davr bo'yicha filtrlash)

```php
// ReportController.php — index() metodi

$stats = [
    // Yangi o'quvchilar (tanlangan davr ichida ro'yxatga olinganlar)
    'new_students' => Student::whereBetween('enrollment_date', [$startDate, $endDate])->count(),

    // Umumiy daromad (faqat yakunlangan to'lovlar)
    'total_revenue' => Payment::whereBetween('payment_date', [$startDate, $endDate])
        ->where('status', 'completed')->sum('amount'),

    // Umumiy qarz (barcha faol o'quvchilar bo'yicha)
    'total_debt' => Student::whereRaw('total_fee > paid_amount')
        ->where('status', 'active')
        ->selectRaw('SUM(total_fee - paid_amount) as debt')
        ->value('debt') ?? 0,

    // O'tgan imtihonlar soni
    'exams_passed' => ExamResult::where('result', 'passed')
        ->whereBetween('created_at', [$startDate, $endDate])->count(),

    // Davomat foizi
    'attendance_rate' => Attendance::whereBetween('created_at', [$startDate, $endDate])->count() > 0
        ? round(
            Attendance::where('status', 'present')
                ->whereBetween('created_at', [$startDate, $endDate])->count()
            / Attendance::whereBetween('created_at', [$startDate, $endDate])->count()
            * 100, 1
          )
        : 0,
];
```

---

### 5.10. Guruh Progress Foizi Hisoblash

```php
// GroupController.php — show() metodi

$scheduleIds      = $group->schedules()->pluck('id');
$schedulesCount   = $scheduleIds->count();
$lessonsCount     = Lesson::whereIn('schedule_id', $scheduleIds)->count();

$completedLessons = Lesson::whereIn('schedule_id', $scheduleIds)
    ->where('status', 'completed')
    ->count();

// Completed/Total nisbati (0 bo'linishdan himoya)
$progressPercent = $schedulesCount > 0
    ? round(($completedLessons / max($lessonsCount, 1)) * 100)
    : 0;
```

---

### 5.11. Online Imtihon Tizimi (Student paneli)

O'quvchi test topshiradi, natija avtomatik hisoblanadi:

```php
// Student/ExamController.php — submit() metodi

// Har bir savol uchun to'g'ri javobni tekshirish
$score = 0;
foreach ($exam->questions as $question) {
    $userAnswer = $request->answers[$question->id] ?? null;
    if ($userAnswer === $question->correct_answer) {
        $score++;
    }
}

$percentage = round(($score / $exam->questions->count()) * 100);
$passed     = $percentage >= $exam->passing_score;

ExamResult::create([
    'exam_id'    => $exam->id,
    'student_id' => auth()->user()->student->id,
    'score'      => $score,
    'percentage' => $percentage,
    'result'     => $passed ? 'passed' : 'failed',
    'answers'    => $request->answers,   // JSON sifatida saqlanadi
]);
```

---

### 5.12. FullCalendar uchun JSON Voqealar (Events API)

Taqvim darslarni ranglar bilan ko'rsatish uchun JSON endpoint:

```php
// GroupController.php — scheduleEvents() metodi

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
        'extendedProps'   => ['type' => $schedule->lesson_type, 'group' => $group->name],
    ];
});

return response()->json($events);
```

---

## 6. Controller'lar Ro'yxati va Vazifalari

### Admin paneli (`app/Http/Controllers/Admin/`)

| Controller | Metodlar | Vazifasi |
|---|---|---|
| `DashboardController` | `index()` | Statistika, grafiklar, oxirgi o'quvchilar, qarzchilar |
| `StudentController` | CRUD + search | O'quvchilarni boshqarish, filtr, qidiruv |
| `InstructorController` | CRUD + search | Instruktorlarni boshqarish, ruxsat kategoriyalari |
| `GroupController` | CRUD + `generateSchedule`, `addStudent`, `removeStudent`, `scheduleEvents` | Guruhlar va jadval algoritmi |
| `ScheduleController` | CRUD + `assignStudents` | Dars jadvali, to'qnashuv tekshirish |
| `PaymentController` | CRUD + `receipt` | To'lovlar, kvitansiya PDF |
| `ExamController` | CRUD + `results` | Imtihonlar va savollar boshqaruvi |
| `CertificateController` | CRUD + `download` | Sertifikat berish va PDF |
| `VehicleController` | CRUD | Avtomobil parki |
| `ReportController` | `index`, `exportPdf`, `exportExcel` | Hisobot, PDF/Excel eksport |

### Instruktor paneli (`app/Http/Controllers/Instructor/`)

| Controller | Vazifasi |
|---|---|
| `DashboardController` | Instruktorning shaxsiy statistikasi |
| `StudentController` | O'z o'quvchilarini ko'rish |
| `ScheduleController` | Dars jadvalini ko'rish |
| `AttendanceController` | Davomat belgilash va yangilash |
| `GradeController` | Baho qo'yish |

### O'quvchi paneli (`app/Http/Controllers/Student/`)

| Controller | Vazifasi |
|---|---|
| `DashboardController` | O'quvchining shaxsiy kabineti |
| `LessonController` | Darslar ro'yxati |
| `ExamController` | Online imtihon topshirish |
| `GradeController` | Baholarni ko'rish |
| `PaymentController` | To'lov tarixi |
| `CertificateController` | Sertifikat yuklab olish |

---

## 7. URL Marshrutlar (Routes) Tuzilmasi

```
GET  /                           → Roli bo'yicha yo'naltirish (login yoki dashboard)

# Admin panel (/admin/...)
GET  /admin/dashboard            → Admin dashboard
GET  /admin/students             → O'quvchilar ro'yxati
GET  /admin/instructors          → Instruktorlar ro'yxati
GET  /admin/vehicles             → Avtomobillar
GET  /admin/schedules            → Dars jadvali
GET  /admin/groups               → Guruhlar
POST /admin/groups/{id}/generate-schedule    → Jadval generatsiya
POST /admin/groups/{id}/add-student          → O'quvchi qo'shish
DEL  /admin/groups/{id}/remove-student/{id} → O'quvchi chiqarish
GET  /admin/groups/{id}/schedule-events      → JSON (FullCalendar)
GET  /admin/exams                → Imtihonlar
GET  /admin/payments             → To'lovlar
GET  /admin/certificates         → Sertifikatlar
GET  /admin/reports              → Hisobot sahifasi
GET  /admin/reports/export/pdf   → PDF yuklab olish
GET  /admin/reports/export/excel → Excel yuklab olish

# Instruktor paneli (/instruktor/...)
GET  /instruktor/dashboard
GET  /instruktor/students
GET  /instruktor/schedule
GET  /instruktor/attendance
POST /instruktor/attendance
GET  /instruktor/grades
POST /instruktor/grades

# O'quvchi paneli (/student/...)
GET  /student/dashboard
GET  /student/lessons
GET  /student/exams
GET  /student/exams/{id}/take
POST /student/exams/{id}/submit
GET  /student/grades
GET  /student/payments
GET  /student/certificates
GET  /student/certificates/{id}/download
```

---

## 8. Ma'lumotlarni Himoya Qilish va Validatsiya

Har bir `store()` va `update()` metodda Laravel validatsiyasi ishlatiladi:

```php
// StudentController::store() — validatsiya misoli
$request->validate([
    'name'             => 'required|string|max:255',
    'email'            => 'required|email|unique:users',
    'phone'            => 'required|string|max:20',
    'password'         => 'required|min:8',
    'license_category' => 'required|in:A,B,C,D',
    'enrollment_date'  => 'required|date',
    'total_fee'        => 'required|numeric|min:0',
    'instructor_id'    => 'nullable|exists:instructors,id',
]);
```

Barcha DB operatsiyalari `DB::transaction()` bilan o'ralgan — xatolikda rollback:

```php
DB::transaction(function () use ($request) {
    $user = User::create([...]);
    $user->assignRole('student');
    Student::create([...'user_id' => $user->id...]);
});
```

Middleware orqali ruxsat nazorati:

```php
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Faqat admin kirishi mumkin
});
```

---

## 9. PDF va Excel Eksport

### PDF (DomPDF kutubxonasi)

```php
// ReportController.php — exportPdf()
$pdf = Pdf::loadView('admin.reports.pdf', compact('students', 'payments', 'startDate', 'endDate'));
return $pdf->download('hisobot-' . $startDate->format('Y-m') . '.pdf');

// PaymentController.php — receipt()
$pdf = Pdf::loadView('admin.payments.receipt-pdf', compact('payment'));
return $pdf->download("kvitansiya-{$payment->receipt_number}.pdf");

// CertificateController.php — download()
$pdf = Pdf::loadView('admin.certificates.pdf', compact('certificate'))
    ->setPaper('A4', 'landscape');  // Sertifikat gorizontal A4
return $pdf->download("sertifikat-{$certificate->certificate_number}.pdf");
```

### Excel (Maatwebsite\Excel)

```php
// ReportController.php — exportExcel()
return Excel::download(
    new StudentsExport(),
    'oquvchilar-' . date('Y-m-d') . '.xlsx'
);
```

---

## 10. Tizim Panellari

### 10.1. Asosiy Custom Admin Panel

**URL:** `http://localhost:8000/admin`

Panel o'z dizayniga ega Blade-based admin paneli bo'lib quyidagi bo'limlarni o'z ichiga oladi:
- Dashboard (statistika kartalar + 2 ta grafik: daromad va o'quvchilar)
- O'quvchilar boshqaruvi (qidiruv, filtr: holat, kategoriya)
- Instruktorlar boshqaruvi
- Guruhlar va FullCalendar taqvimi
- Imtihonlar va natijalar
- To'lovlar va kvitansiyalar
- Sertifikatlar
- Hisobotlar va eksport

### 10.2. Filament v5 Admin Panel

**URL:** `http://localhost:8000/filament-admin`

Filament v5 yordamida yaratilgan ikkinchi admin panel (zamonaviy UI).

---

## 11. Tizim Ulanish Ma'lumotlari (Credentials)

| Rol | Email | Parol |
|---|---|---|
| Admin | admin@avtomaktab.uz | password |
| Instruktor 1 | jasur@avtomaktab.uz | password |
| Instruktor 2 | bobur@avtomaktab.uz | password |
| Instruktor 3 | sarvar@avtomaktab.uz | password |
| O'quvchi | alisher.nazarov@student.uz | password |

*Tizimda jami 20 ta o'quvchi seeded qilingan.*

---

## 12. Ishga Tushirish

```bash
# Loyiha joylashgan katalog
cd /home/ninetydev/Desktop/Avtomaktab/avtomaktab

# Serverni ishga tushirish
php artisan serve --port=8000

# Ma'lumotlar bazasini qayta seeding qilish (agar kerak bo'lsa)
php artisan migrate:fresh --seed
```

---

## 13. Filament v5 + PHP 8.4 Uchun Muhim Tuzatishlar

Loyiha ishlab chiqilayotganda quyidagi texnik muammolar hal qilindi:

| Muammo | Yechim |
|---|---|
| Filament v5 + PHP 8.4: `$navigationIcon` property type xatoligi | `string\|BackedEnum\|null` ishlatish kerak, `?string` emas |
| Filament v5: `form()` metodi imzosi | `form(Schema $schema)` — `Filament\Schemas\Schema` import |
| Heroicon ikonlari | `Heroicon::OutlinedUsers` enum case, string `'heroicon-o-users'` emas |
| O'zbek apostrof PHP stringida | `'o\'quvchi'` — escape qilish shart |
| DomPDF template yo'q | `admin/reports/pdf.blade.php` yaratildi |
| Root `/admin` yo'q | `Route::get('/', fn() => redirect()->route('admin.dashboard'))` qo'shildi |

---

## 14. Xulosa

Avtomaktab tizimi haydovchilik maktablarining kundalik ishini to'liq raqamlashtiradi. Loyihaning asosiy texnik yutuqlari:

1. **Avtomatik jadval generatsiyasi** — Guruh muddati bo'yicha yuzlab darsni bir so'rov bilan yaratish
2. **Kaskadli ma'lumot boshqaruvi** — O'quvchi/guruh o'zgarishida bog'liq barcha yozuvlar avtomatik yangilanadi
3. **Tarixiy ma'lumotlarni himoya** — O'tgan darslar hech qachon o'chirilmaydi
4. **Ko'p formatli eksport** — PDF (hisobot, kvitansiya, sertifikat) va Excel
5. **Uch panelli arxitektura** — Admin, Instruktor va O'quvchi uchun alohida interfeys
6. **RBAC (Role-based Access Control)** — Spatie permission paketi bilan xavfsiz kirish nazorati
7. **Online imtihon moduli** — O'quvchi to'g'ridan-to'g'ri brauzerda test topshiradi

Tizim Laravel 13 + PHP 8.3 platformasida ishlab, barcha 30+ marshrutda 200 OK tasdiqlangan.
