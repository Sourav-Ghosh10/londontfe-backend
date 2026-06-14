<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'username',
    'email',
    'password',
    'is_admin_eligible',
    'fname',
    'lname',
    'address',
    'whats',
    'calender_link',
    'user_type',
    'status',
    'create_date',
    'title',
    'changed_status',
    'conversation_message',
    'conversation_date'
])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'user';

    public $timestamps = false;

    public function details()
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }
}
