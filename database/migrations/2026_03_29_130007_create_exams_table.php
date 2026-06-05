<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Imtihonlar jadvali
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Imtihon nomi
            $table->enum('type', ['theory', 'practical', 'stop']); // Nazariy, amaliy, STOP
            $table->string('license_category', 5)->default('B');
            $table->datetime('exam_date');
            $table->integer('duration_minutes')->default(30);
            $table->integer('passing_score')->default(70); // O'tish bali (%)
            $table->integer('total_questions')->nullable(); // Nazariy imtihon uchun
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->foreignId('created_by')->constrained('users');
            $table->text('instructions')->nullable();
            $table->timestamps();
        });

        // Imtihon savollari
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->json('options'); // ['A'=>'...', 'B'=>'...', 'C'=>'...', 'D'=>'...']
            $table->string('correct_answer', 1); // A, B, C, D
            $table->integer('order')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exams');
    }
};
