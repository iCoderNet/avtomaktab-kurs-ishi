<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Sertifikatlar jadvali
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_number')->unique(); // Sertifikat raqami
            $table->string('license_category', 5)->default('B');
            $table->date('issued_date');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'revoked', 'expired'])->default('active');
            $table->string('pdf_path')->nullable(); // PDF fayli yo'li
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
