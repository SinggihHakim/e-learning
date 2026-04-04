<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['hadir', 'telat', 'izin', 'tidak_hadir'])->default('tidak_hadir');
            $table->timestamps();
            $table->unique(['attendance_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_students');
    }
};
