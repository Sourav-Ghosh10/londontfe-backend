<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'seo';
    public $timestamps = false;
    protected $fillable = [
        'title', 'page_type', 'reference_id', 'meta_keywords', 'meta_description', 'status', 'create_date', 'last_updated'
    ];
}
