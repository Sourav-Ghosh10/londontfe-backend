<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $table = 'user_details';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'company_id',
        'category_ids',
        'country',
        'contact_no_code',
        'contact_no',
        'phone_code',
        'passport_no',
        'phone',
        'sex',
        'class_code',
        'accounting_details',
        'image_name',
        'image_ext',
        'reward_point',
        'notes',
        'job_title',
        'passport_image',
        'first_name',
        'last_name',
        'whatsapp',
        'address',
        'bio',
        'status',
        'role',
        'calendar_link',
        'short_order',
        'show_admin_profile'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
