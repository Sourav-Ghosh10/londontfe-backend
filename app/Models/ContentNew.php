<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ContentNew extends Model
{
    protected $table = 'content_new';

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'last_update';

    protected $fillable = [
        'parent_page_id',
        'title',
        'content',
        'menu_title',
        'url',
        'password',
        'page_banner',
        'status',
    ];

    /**
     * Get the banner URL from S3.
     */
    public function getBannerUrlAttribute()
    {
        if (!$this->page_banner) {
            return null;
        }

        if (filter_var($this->page_banner, FILTER_VALIDATE_URL)) {
            return $this->page_banner;
        }

        return Storage::disk('s3')->url($this->page_banner);
    }

    /**
     * Parent relationship.
     */
    public function parent()
    {
        return $this->belongsTo(ContentNew::class, 'parent_page_id');
    }
}
