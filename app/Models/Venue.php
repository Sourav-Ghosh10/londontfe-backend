<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $table = 'venue';
    
    public $timestamps = false;

    protected $fillable = [
        'venue_name',
        'venue_seo_name',
        'venue_address',
        'venue_text',
        'region',
        'venue_image',
        'banner_image',
        'venue_featured_image',
        'venue_featured_text',
        'flag_image',
        'is_featured',
        'status',
        'seals_status',
        'meta_title',
        'meta_description',
        'venue_type',
        'create_date',
        'last_updated',
    ];
}
