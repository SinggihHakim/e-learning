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
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreignId('component_id')->nullable()->constrained('grade_components')->nullOnDelete();
        });
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('component_id')->nullable()->constrained('grade_components')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['component_id']);
            $table->dropColumn('component_id');
        });
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['component_id']);
            $table->dropColumn('component_id');
        });
    }
};
