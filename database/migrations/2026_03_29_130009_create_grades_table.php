<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Baholar jadvali
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('instructor_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['theory', 'practical', 'behavior', 'final']); // Baho turi
            $table->integer('score'); // 1-5 yoki 0-100
            $table->string('grade_letter')->nullable(); // A, B, C, D, F
            $table->text('comment')->nullable();
            $table->date('graded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
