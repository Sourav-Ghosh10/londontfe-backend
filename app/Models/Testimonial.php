<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    public $timestamps = false;

    protected $fillable = [
        'author_name',
        'testimonial_text',
        'author_description',
        'created_on',
        'status',
    ];
}
