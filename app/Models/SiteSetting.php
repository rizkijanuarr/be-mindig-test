<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'app_name',
        'brand_name',
        'brand_logo',
        'locale',
        'fallback_locale',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
