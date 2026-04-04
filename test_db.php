<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$materials = App\Models\Material::whereNotNull('video_link')->get();
foreach($materials as $m) {
    file_put_contents('test_db_out.txt', $m->title . " -> " . $m->video_link . "\n", FILE_APPEND);
}
