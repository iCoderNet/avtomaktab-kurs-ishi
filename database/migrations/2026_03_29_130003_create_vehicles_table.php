<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Avtomobillar parki jadvali
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique(); // Davlat raqami
            $table->string('brand'); // Toyota, Chevrolet, etc.
            $table->string('model');
            $table->year('year');
            $table->string('color')->nullable();
            $table->string('vin_number', 17)->nullable()->unique();
            $table->string('license_category', 5)->default('B'); // Qaysi kategoriya uchun
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired'])->default('available');
            $table->integer('mileage')->default(0); // Yurgan masofasi (km)
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->date('technical_inspection_expiry')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('assigned_instructor_id')->nullable()->constrained('instructors')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
