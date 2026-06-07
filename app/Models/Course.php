<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course';
    public $timestamps = false; // Using create_date and last_updated

    protected $fillable = [
        'training_type', 'find_course_id', 'course_unique_id', 'accredible_id', 'course_name', 'price_tier_id', 
        'course_venue', 'course_tag_line', 'seo_name', 'overview', 'wsa', 'course_duration', 'course_duration_type', 
        'course_material', 'course_meterial_content', 'course_objective', 'certification', 'specialties', 
        'prerequisites', 'career_advance', 'photo', 'thumbnail_photo', 'cpd_hours', 'map_start_date', 'frequency_day', 
        'stop_scheduling', 'is_featured', 'is_offer', 'offer_type', 'offer_value', 'feature_image', 'alt_tag', 
        'price_corporate_dollar', 'price_corporate_gbp', 'price_individual_dollar', 'price_individual_gbp', 
        'is_publish', 'is_publish_reed', 'is_publish_laimoon', 'is_publish_bayt', 'is_publish_emagister', 'status', 
        'rating', 'is_certified', 'course_type', 'keyfact_one', 'keyfact_two', 'keyfact_three', 'keyfact_four', 
        'keyfact_five', 'secondary_category', 'create_date', 'last_updated'
    ];

    public function categories()
    {
        return $this->belongsToMany(CourseCategory::class, 'course_category_assoc', 'course_id', 'category_id');
    }

    public function priceTier()
    {
        return $this->belongsTo(PriceTier::class, 'price_tier_id');
    }
}
