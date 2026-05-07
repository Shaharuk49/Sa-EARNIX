<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReferralRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'upline_user_id',
        'generation',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function upline()
    {
        return $this->belongsTo(User::class, 'upline_user_id');
    }
}