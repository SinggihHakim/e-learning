<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

DB::statement('SET FOREIGN_KEY_CHECKS=0;');
Schema::dropIfExists('quiz_answers');
Schema::dropIfExists('quiz_attempts');
Schema::dropIfExists('quiz_question_options');
Schema::dropIfExists('quiz_questions');
Schema::dropIfExists('quizzes');
DB::table('migrations')->where('migration', 'like', '%quiz%')->delete();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');
echo "Cleared quiz tables successfully.\n";
