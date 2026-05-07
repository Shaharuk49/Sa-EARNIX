<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficialLink extends Model
{
    protected $fillable = [
        'key_name',
        'title',
        'url',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
