<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    protected $fillable = [
        'code',
        'type',
        'discount_type',
        'discount_value',
        'course_id',
        'venue_id',
        'max_usage',
        'used_usage',
        'status',
    ];
}
