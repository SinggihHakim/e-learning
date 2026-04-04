<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nis')->nullable();
            $table->string('gender')->nullable(); // Laki-laki / Perempuan
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('religion')->nullable();
            $table->string('phone')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->text('parent_address')->nullable();
            $table->string('father_job')->nullable();
            $table->string('mother_job')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nis', 'gender', 'birth_place', 'birth_date', 'address', 
                'religion', 'phone', 'father_name', 'mother_name', 
                'parent_address', 'father_job', 'mother_job'
            ]);
        });
    }
};
