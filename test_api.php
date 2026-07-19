<?php
$json = file_get_contents('http://localhost:8000/api/v1/courses?coupon=COURSE10&sort=date_asc&per_page=50');
$data = json_decode($json, true);
foreach ($data['data']['data'] as $item) {
    if (strpos($item['course_name'], 'A-Z') !== false) {
        echo "Found: " . $item['course_name'] . "\n";
        echo "Date: " . $item['start_date'] . "\n";
        echo "Coupon Valid: " . ($item['is_coupon_valid'] ? 'true' : 'false') . "\n";
        echo "Discount Value: " . $item['discount_value'] . "\n";
    }
}
