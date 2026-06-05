<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Vehicle;
use App\Models\Schedule;
use App\Models\Lesson;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Grade;
use App\Models\Certificate;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Rollarni yaratish
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $instructorRole = Role::firstOrCreate(['name' => 'instructor']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        // Admin foydalanuvchi
        $admin = User::firstOrCreate(
            ['email' => 'admin@avtomaktab.uz'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'phone' => '+998901234567',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Instruktorlar yaratish
        $instructorData = [
            ['name' => 'Jasur Toshmatov', 'email' => 'jasur@avtomaktab.uz', 'phone' => '+998901111111'],
            ['name' => 'Bobur Yusupov', 'email' => 'bobur@avtomaktab.uz', 'phone' => '+998902222222'],
            ['name' => 'Sarvar Karimov', 'email' => 'sarvar@avtomaktab.uz', 'phone' => '+998903333333'],
        ];

        $instructors = [];
        foreach ($instructorData as $i => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'phone' => $data['phone'],
                    'status' => 'active',
                    'gender' => 'male',
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('instructor');

            $instructor = Instructor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_number' => 'INS-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'license_categories' => ['B', 'C'],
                    'driving_license_number' => 'DL' . rand(1000000, 9999999),
                    'driving_license_expiry' => Carbon::now()->addYears(3),
                    'experience_years' => rand(3, 15),
                    'status' => 'active',
                    'hire_date' => Carbon::now()->subYears(rand(1, 5)),
                    'salary' => rand(3000000, 6000000),
                    'max_students' => 15,
                ]
            );
            $instructors[] = $instructor;
        }

        // Avtomobillar
        $vehicleData = [
            ['brand' => 'Chevrolet', 'model' => 'Cobalt', 'year' => 2022, 'plate_number' => '01A123BC', 'color' => 'Oq'],
            ['brand' => 'Chevrolet', 'model' => 'Nexia 3', 'year' => 2021, 'plate_number' => '01B456DE', 'color' => 'Kumush'],
            ['brand' => 'Chevrolet', 'model' => 'Malibu', 'year' => 2023, 'plate_number' => '01C789FG', 'color' => 'Qora'],
            ['brand' => 'Hyundai', 'model' => 'Accent', 'year' => 2020, 'plate_number' => '01D012HI', 'color' => 'Ko\'k'],
        ];

        foreach ($vehicleData as $i => $vData) {
            Vehicle::firstOrCreate(
                ['plate_number' => $vData['plate_number']],
                [
                    'brand' => $vData['brand'],
                    'model' => $vData['model'],
                    'year' => $vData['year'],
                    'color' => $vData['color'],
                    'license_category' => 'B',
                    'status' => 'available',
                    'mileage' => rand(10000, 80000),
                    'last_maintenance_date' => Carbon::now()->subMonths(rand(1, 6)),
                    'next_maintenance_date' => Carbon::now()->addMonths(rand(1, 6)),
                    'insurance_expiry' => Carbon::now()->addYear(),
                    'technical_inspection_expiry' => Carbon::now()->addMonths(rand(6, 18)),
                    'assigned_instructor_id' => $instructors[$i % count($instructors)]->id ?? null,
                ]
            );
        }

        // O'quvchilar yaratish (20 ta)
        $studentNames = [
            'Alisher Nazarov', 'Dilnoza Umarova', 'Sherzod Mirzayev', 'Gulnora Hasanova',
            'Firdavs Tursunov', 'Malika Qodirov', 'Jahongir Rakhimov', 'Zulfiya Islamov',
            'Otabek Xolmatov', 'Nargiza Yusupov', 'Timur Abdullayev', 'Shahnoza Ergashev',
            'Bekzod Qosimov', 'Iroda Normatov', 'Ulugbek Mansur', 'Feruza Sattorov',
            'Rustam Kalandarov', 'Dilrabo Eshmatov', 'Sanjar Norqo\'ziyev', 'Aziza Hamidov',
        ];

        $students = [];
        foreach ($studentNames as $i => $name) {
            $email = strtolower(str_replace(' ', '.', $name)) . '@student.uz';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'phone' => '+9989' . rand(01000000, 99999999),
                    'status' => 'active',
                    'gender' => $i % 3 === 1 ? 'female' : 'male',
                    'birth_date' => Carbon::now()->subYears(rand(18, 35)),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('student');

            $instructor = $instructors[$i % count($instructors)];
            $enrollDate = Carbon::now()->subMonths(rand(1, 6));
            $totalFee = 2500000; // 2.5 mln so'm
            $paidAmount = rand(0, 100) < 70
                ? $totalFee
                : rand(500000, 2000000);

            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'student_number' => 'STD-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'passport_series' => 'AA',
                    'passport_number' => str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'license_category' => 'B',
                    'instructor_id' => $instructor->id,
                    'status' => $i < 16 ? 'active' : ($i < 18 ? 'completed' : 'inactive'),
                    'enrollment_date' => $enrollDate,
                    'expected_graduation' => $enrollDate->copy()->addMonths(3),
                    'total_fee' => $totalFee,
                    'paid_amount' => $paidAmount,
                ]
            );
            $students[] = $student;

            // To'lovlar qo'shish
            if ($paidAmount > 0) {
                Payment::create([
                    'student_id' => $student->id,
                    'receipt_number' => 'RCP-' . date('Y') . '-' . str_pad($i * 3 + 1, 5, '0', STR_PAD_LEFT),
                    'amount' => $paidAmount,
                    'type' => 'tuition',
                    'payment_method' => ['cash', 'card', 'transfer'][rand(0, 2)],
                    'status' => 'completed',
                    'payment_date' => $enrollDate->copy()->addDays(rand(0, 30)),
                    'month_for' => $enrollDate->format('Y-m'),
                    'received_by' => $admin->id,
                ]);
            }
        }

        // Dars jadvallari va darslar yaratish
        foreach ($instructors as $instructor) {
            for ($day = 0; $day < 7; $day++) {
                $date = Carbon::now()->startOfWeek()->addDays($day);
                if ($date->isWeekend()) continue;

                // Nazariy dars
                $schedule = Schedule::create([
                    'instructor_id' => $instructor->id,
                    'lesson_type' => 'theory',
                    'date' => $date,
                    'start_time' => '09:00',
                    'end_time' => '11:00',
                    'location' => '1-sinf xona',
                    'max_students' => 10,
                    'status' => $date->isPast() ? 'completed' : 'scheduled',
                ]);

                // Bu instruktorning o'quvchilari uchun darslar
                $instructorStudents = collect($students)->filter(fn($s) => $s->instructor_id === $instructor->id)->take(8);
                foreach ($instructorStudents as $student) {
                    $lesson = Lesson::create([
                        'schedule_id' => $schedule->id,
                        'student_id' => $student->id,
                        'instructor_id' => $instructor->id,
                        'type' => 'theory',
                        'date' => $date,
                        'start_time' => '09:00',
                        'end_time' => '11:00',
                        'status' => $date->isPast() ? 'completed' : 'scheduled',
                        'duration_minutes' => 120,
                    ]);

                    // O'tgan darslar uchun davomat
                    if ($date->isPast()) {
                        Attendance::create([
                            'lesson_id' => $lesson->id,
                            'student_id' => $student->id,
                            'status' => rand(0, 100) < 85 ? 'present' : (rand(0, 1) ? 'absent' : 'late'),
                            'marked_by' => $instructor->user_id,
                        ]);

                        // Baho
                        Grade::create([
                            'student_id' => $student->id,
                            'lesson_id' => $lesson->id,
                            'instructor_id' => $instructor->id,
                            'type' => 'theory',
                            'score' => rand(60, 100),
                            'grade_letter' => Grade::getLetterGrade(rand(60, 100)),
                            'graded_at' => $date,
                        ]);
                    }
                }
            }
        }

        // Imtihon yaratish
        $exam = Exam::create([
            'name' => "Nazariy imtihon - 2026 yil 1-chora",
            'type' => 'theory',
            'license_category' => 'B',
            'exam_date' => Carbon::now()->addWeek(),
            'duration_minutes' => 30,
            'passing_score' => 70,
            'total_questions' => 20,
            'status' => 'upcoming',
            'created_by' => $admin->id,
            'instructions' => 'Imtihon 20 ta savoldan iborat. Har bir savol uchun 1.5 daqiqa vaqt beriladi.',
        ]);

        // Savollar qo'shish
        $questions = [
            ['q' => "Yo'l harakatida piyodalar qanday harakat qilishi kerak?", 'a' => 'B', 'opts' => ['A' => "Yo'lning o'ng tomonida", 'B' => "Yo'lning chap tomonida, qarama-qarshi transport oqimiga yuzlab", 'C' => 'Istalgan tomonda', 'D' => 'Faqat yoqimli havoda']],
            ['q' => "Qizil chiroqda to'xtash chizig'idan qancha masofada to'xtash kerak?", 'a' => 'A', 'opts' => ['A' => '1 metrdan kam bo\'lmagan masofada', 'B' => '2 metrdan ko\'proq', 'C' => 'Chiroqdan 5 metr', 'D' => 'Istalgan masofada']],
            ['q' => "Shaharda transport vositasining ruxsat etilgan tezligi necha km/soat?", 'a' => 'C', 'opts' => ['A' => '60 km/soat', 'B' => '80 km/soat', 'C' => '60 km/soat (umumiy holda)', 'D' => '100 km/soat']],
        ];

        foreach ($questions as $i => $q) {
            \App\Models\ExamQuestion::create([
                'exam_id' => $exam->id,
                'question' => $q['q'],
                'options' => $q['opts'],
                'correct_answer' => $q['a'],
                'order' => $i + 1,
            ]);
        }

        // O'tgan imtihon natijalari
        $pastExam = Exam::create([
            'name' => "Nazariy imtihon - 2026 yil 1-bosqich",
            'type' => 'theory',
            'license_category' => 'B',
            'exam_date' => Carbon::now()->subWeeks(2),
            'duration_minutes' => 30,
            'passing_score' => 70,
            'total_questions' => 20,
            'status' => 'completed',
            'created_by' => $admin->id,
        ]);

        foreach (array_slice($students, 0, 10) as $student) {
            $score = rand(50, 100);
            ExamResult::create([
                'exam_id' => $pastExam->id,
                'student_id' => $student->id,
                'score' => $score,
                'correct_answers' => (int)($score / 5),
                'total_questions' => 20,
                'result' => $score >= 70 ? 'passed' : 'failed',
                'attempt_number' => 1,
                'started_at' => $pastExam->exam_date,
                'finished_at' => $pastExam->exam_date->addMinutes(rand(20, 30)),
                'examined_by' => $admin->id,
            ]);
        }

        // Sertifikatlar (tugatgan o'quvchilar uchun)
        foreach (array_slice($students, 16, 2) as $i => $student) {
            Certificate::create([
                'student_id' => $student->id,
                'certificate_number' => 'CERT-2026-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'license_category' => 'B',
                'issued_date' => Carbon::now()->subWeeks(2),
                'expiry_date' => Carbon::now()->addYears(10),
                'status' => 'active',
                'issued_by' => $admin->id,
            ]);
        }

        $this->command->info('✅ Barcha ma\'lumotlar muvaffaqiyatli yaratildi!');
        $this->command->info('📧 Admin: admin@avtomaktab.uz | Parol: password');
        $this->command->info('📧 Instruktor: jasur@avtomaktab.uz | Parol: password');
        $this->command->info('📧 O\'quvchi: alisher.nazarov@student.uz | Parol: password');
    }
}
