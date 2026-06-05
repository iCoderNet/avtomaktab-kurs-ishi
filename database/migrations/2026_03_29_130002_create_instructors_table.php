<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Instruktorlar jadvali
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('employee_number')->unique(); // Xodim raqami
            $table->json('license_categories'); // ['A', 'B', 'C']
            $table->string('driving_license_number')->nullable();
            $table->date('driving_license_expiry')->nullable();
            $table->integer('experience_years')->default(0);
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->date('hire_date');
            $table->decimal('salary', 12, 2)->default(0);
            $table->integer('max_students')->default(10); // Maksimal o'quvchilar soni
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
