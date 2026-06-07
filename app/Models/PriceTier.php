<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceTier extends Model
{
    protected $table = 'price_tier';

    protected $fillable = [
        'tier_name', 'company_ids', 'tier_des', 'base_rate', 'daily_rate', 'is_company_price'
    ];
}
