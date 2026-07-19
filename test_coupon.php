<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$promo = \App\Models\Promocode::where('code', 'COURSE10')->first();
echo "Promo: course_id=" . $promo->course_id . ", venue_id=" . $promo->venue_id . ", date=" . $promo->date . "\n";

$course = \App\Models\Course::where('course_name', 'like', '%A-Z%')->first();
echo "Course: id=" . $course->id . ", name=" . $course->course_name . "\n";

$item = DB::table('course_date_venue as cdv')
    ->join('course as c', 'cdv.course_id', '=', 'c.id')
    ->where('c.id', $course->id)
    ->where('cdv.start_date', '2026-09-14')
    ->select('cdv.id as schedule_id', 'c.id as course_id', 'cdv.start_date', 'cdv.venue_id')
    ->first();

echo "Item: course_id=" . $item->course_id . ", venue_id=" . $item->venue_id . ", start_date=" . $item->start_date . "\n";

$isValid = true;
if ($promo->course_id && $promo->course_id != $item->course_id) {
    echo "Failed on course_id\n";
    $isValid = false;
}
if ($promo->venue_id && $promo->venue_id != $item->venue_id) {
    echo "Failed on venue_id\n";
    $isValid = false;
}
if ($promo->date && $promo->date != $item->start_date) {
    echo "Failed on date\n";
    $isValid = false;
}

echo "isValid: " . ($isValid ? 'true' : 'false') . "\n";
