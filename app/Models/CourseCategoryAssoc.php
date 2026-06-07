<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategoryAssoc extends Model
{
    protected $table = 'course_category_assoc';
    public $timestamps = false;

    protected $fillable = [
        'course_id', 'category_id', 'type'
    ];
}
