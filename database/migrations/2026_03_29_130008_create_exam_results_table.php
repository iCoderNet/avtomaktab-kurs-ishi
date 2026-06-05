<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Imtihon natijalari
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->integer('score')->default(0); // Foizda
            $table->integer('correct_answers')->nullable();
            $table->integer('total_questions')->nullable();
            $table->enum('result', ['passed', 'failed', 'absent'])->default('absent');
            $table->integer('attempt_number')->default(1); // Necha marta qatnashdi
            $table->json('answers')->nullable(); // {'q1': 'A', 'q2': 'B', ...}
            $table->datetime('started_at')->nullable();
            $table->datetime('finished_at')->nullable();
            $table->text('examiner_notes')->nullable();
            $table->foreignId('examined_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
