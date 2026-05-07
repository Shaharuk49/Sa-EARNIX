<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship: BonusSection has many BonusSectionVideos
    public function videos()
    {
        return $this->hasMany(BonusSectionVideo::class)->orderBy('sort_order');
    }

    // Relationship: BonusSection has many BonusSectionRules
    public function rules()
    {
        return $this->hasMany(BonusSectionRule::class);
    }

    // Check if user has unlocked this section
    public function isUnlockedFor($userId)
    {
        $user = User::find($userId);
        
        foreach ($this->rules as $rule) {
            if (!$rule->isMetBy($user)) {
                return false;
            }
        }
        
        return true;
    }
}
