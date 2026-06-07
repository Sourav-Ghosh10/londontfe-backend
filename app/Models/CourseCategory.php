<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    protected $table = 'category';
    
    public $timestamps = false;

    protected $fillable = [
        'category_name',
        'category_seo_name',
        'category_tag_line',
        'level_page_text',
        'parent_category',
        'image_name',
        'course_list_image',
        'course_details_image',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'category_content',
        'featured_category',
        'featured_image',
        'banner_image',
        'category_txt',
        'is_3_for_2_offer',
        'status',
        'create_date',
        'last_updated',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_category_assoc', 'category_id', 'course_id');
    }
}
