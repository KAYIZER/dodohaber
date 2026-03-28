<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name',
        'label',
        'is_active',
        'settings',
        'slug',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];
}
