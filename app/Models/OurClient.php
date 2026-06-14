<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurClient extends Model
{
    protected $table = 'our_clients';

    protected $fillable = [
        'logo',
        'alt_text',
        'order',
        'status',
    ];

    /**
     * Get the logo URL from S3.
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }

        if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
            return $this->logo;
        }

        // Check if the logo is a legacy file path (like part1.png) or full path
        // S3 storage URL resolution:
        return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->logo);
    }
}
