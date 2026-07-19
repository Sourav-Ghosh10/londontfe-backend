<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$course = \App\Models\Course::where('course_name', 'like', '%A-Z%')->first();
$pt = DB::table('price_tier')->where('id', $course->price_tier_id)->first();
echo "Price tier: base_rate=" . $pt->base_rate . ", daily_rate=" . $pt->daily_rate . "\n";
