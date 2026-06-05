<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// To'lovlar jadvali
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->unique(); // Kvitansiya raqami
            $table->decimal('amount', 12, 2); // To'lov miqdori
            $table->enum('type', ['tuition', 'exam_fee', 'certificate', 'penalty', 'other'])->default('tuition');
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'online'])->default('cash');
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('completed');
            $table->date('payment_date');
            $table->string('month_for')->nullable(); // Qaysi oy uchun to'lov
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
