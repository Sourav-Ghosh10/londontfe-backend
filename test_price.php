<?php
$courseId = 1188;
$course = DB::table('course')->where('id', $courseId)->first(['id', 'course_duration', 'price_tier_id']);
$basePrice = 0;
if ($course) {
    $tier = DB::table('price_tier')->where('id', $course->price_tier_id)->first();
    dump("Tier: ", $tier);
    if ($tier) {
        $days = (int) $course->course_duration;
        $base_rate = ($tier->base_rate * (round($days / 5)));
        $daily_rate = $tier->daily_rate;
        $basePrice = $base_rate + ($daily_rate * $days);
    }
}
dump("Base Price: ", $basePrice);

$dates = DB::table('course_date_venue')->where('course_id', $courseId)->get();
$locationBands = DB::table('location_band')->get(['location_band_type', 'adjustment', 'venue']);

foreach($dates as $d) {
    $price = $basePrice;
    $venue_id = $d->venue_id;

    $adjustment = 0;
    $type = '';
    foreach ($locationBands as $band) {
        $bandVenues = explode(',', $band->venue);
        if (in_array($venue_id, $bandVenues)) {
            $adjustment = $band->adjustment;
            $type = $band->location_band_type;
            break;
        }
    }

    if (!empty($adjustment)) {
        if ($type === 'plus') {
            $price += ($price * $adjustment) / 100;
        } elseif ($type === 'minus') {
            $price -= ($price * $adjustment) / 100;
        }
    }

    $price = round($price / 100) * 100;
    dump("Venue ID: " . $venue_id . " Price: " . $price);
}
