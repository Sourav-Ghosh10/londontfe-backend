<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationBand extends Model
{
    protected $table = 'location_band';

    protected $fillable = [
        'location_band_name',
        'location_band_type',
        'venue',
        'adjustment'
    ];
}
