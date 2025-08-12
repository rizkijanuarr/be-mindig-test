<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'information',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
