<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoResponceContent extends Model
{
    protected $table = 'auto_responce_content';

    const CREATED_AT = null;
    const UPDATED_AT = 'update_date';

    protected $fillable = [
        'form_name',
        'mail_subject',
        'mail_preview',
        'mail_content',
        'default_content',
        'content_status',
    ];
}
