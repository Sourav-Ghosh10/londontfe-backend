<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AccreditationContent extends Model
{
    protected $table = 'accreditation_content';

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'last_updated';

    protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'accreditation_name',
        'logo',
        'content',
        'heading',
        'members',
        'countries',
        'chapters',
        'tag_line',
        'status',
        'display_order',
    ];

    /**
     * Get logo URL from S3.
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }

        if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
            return $this->logo;
        }

        // Check if path contains directory or if it is just a filename
        if (strpos($this->logo, '/') !== false) {
            return Storage::disk('s3')->url($this->logo);
        }

        // Default bucket directory for accreditation logos if it's just a filename
        return Storage::disk('s3')->url($this->logo);
    }
}
