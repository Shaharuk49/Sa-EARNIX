<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLeaderBadge extends Model
{
    protected $fillable = [
        'user_id', 'leader_badge_id', 'awarded_at',
    ];

    protected $casts = [
        'awarded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(LeaderBadge::class, 'leader_badge_id');
    }
}
