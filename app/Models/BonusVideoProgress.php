<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusVideoProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bonus_section_video_id',
        'watched_at',
    ];

    protected $casts = [
        'watched_at' => 'datetime',
    ];

    // Relationship: BonusVideoProgress belongs to one User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: BonusVideoProgress belongs to one BonusSectionVideo
    public function video()
    {
        return $this->belongsTo(BonusSectionVideo::class);
    }
}
