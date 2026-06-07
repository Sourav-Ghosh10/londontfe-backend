<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'media_type',
        'media_title',
        'alt_text',
        'file_path',
    ];
}
