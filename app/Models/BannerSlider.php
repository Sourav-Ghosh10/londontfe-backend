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
     * Boot the model and rebuild API cache on updates
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($banner) {
            self::updateApiCache();
        });

        static::deleted(function ($banner) {
            self::updateApiCache();
        });
    }

    /**
     * Update the Redis API cache with the latest active banners.
     */
    public static function updateApiCache()
    {
        $banners = self::where('status', 'Active')
            ->orderBy('sequence', 'asc')
            ->get()
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->alt_tag ?? '',
                    'image' => $banner->image_url,
                    'link' => $banner->url ?? '',
                ];
            })->toArray();

        \Illuminate\Support\Facades\Cache::store('redis')->put('api_active_banners_v1', $banners, 3600);
        
        return $banners;
    }

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
