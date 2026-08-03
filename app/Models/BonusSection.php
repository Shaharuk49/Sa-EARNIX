<?php

namespace App\Models;

use App\Models\BonusVideoProgress;
use App\Models\User;
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

    public function previousSections()
    {
        return self::where('is_active', true)
            ->where('sort_order', '<', $this->sort_order)
            ->orderBy('sort_order');
    }

    public function hasPreviousSectionsCompletedBy($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        foreach ($this->previousSections()->get() as $section) {
            $videoIds = $section->videos()->pluck('id')->toArray();
            if (count($videoIds) === 0) {
                return false;
            }

            $watchedCount = BonusVideoProgress::where('user_id', $userId)
                ->whereIn('bonus_section_video_id', $videoIds)
                ->count();

            if ($watchedCount < count($videoIds)) {
                return false;
            }
        }

        return true;
    }

    // Check if user has unlocked this section
    public function isUnlockedFor($userId)
    {
        if (!$this->hasPreviousSectionsCompletedBy($userId)) {
            return false;
        }

        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        foreach ($this->rules as $rule) {
            if (!$rule->isMetBy($user)) {
                return false;
            }
        }

        return true;
    }
}
