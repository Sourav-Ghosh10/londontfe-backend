<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BannerSlider extends Model
{
    protected $table = 'bannerslider';

    protected $fillable = [
        'image',
        'mobile_image',
        'alt_tag',
        'sequence',
        'url',
        'status',
    ];

    /**
     * Get the desktop image URL from S3.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return Storage::disk('s3')->url($this->image);
    }

    /**
     * Get the mobile image URL from S3.
     */
    public function getMobileImageUrlAttribute()
    {
        if (!$this->mobile_image) {
            return null;
        }

        if (filter_var($this->mobile_image, FILTER_VALIDATE_URL)) {
            return $this->mobile_image;
        }

        return Storage::disk('s3')->url($this->mobile_image);
    }
}
