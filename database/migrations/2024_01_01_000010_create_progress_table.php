<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->boolean('completed')->default(false);
            $table->timestamps();
            $table->unique(['student_id', 'material_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
