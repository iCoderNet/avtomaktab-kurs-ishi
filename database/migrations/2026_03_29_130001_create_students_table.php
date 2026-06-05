<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// O'quvchilar jadvali
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('student_number')->unique(); // O'quvchi raqami
            $table->string('passport_series', 10)->nullable();
            $table->string('passport_number', 20)->nullable();
            $table->date('passport_issued_date')->nullable();
            $table->string('passport_issued_by')->nullable();
            $table->string('license_category', 10)->default('B'); // A, B, C, D
            $table->foreignId('instructor_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['active', 'inactive', 'completed', 'expelled'])->default('active');
            $table->date('enrollment_date');
            $table->date('expected_graduation')->nullable();
            $table->decimal('total_fee', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->json('documents')->nullable(); // Hujjatlar ro'yxati
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
