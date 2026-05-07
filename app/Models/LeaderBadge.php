<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderBadge extends Model
{
    protected $fillable = [
        'name', 'slug', 'sort_order', 'icon', 'condition_text', 'prize_text',
    ];

    public function userBadges()
    {
        return $this->hasMany(UserLeaderBadge::class);
    }
}
