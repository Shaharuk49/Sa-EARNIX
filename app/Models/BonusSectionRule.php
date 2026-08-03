<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusSectionRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bonus_section_id',
        'rule_type',
        'rule_value',
    ];

    // Relationship: BonusSectionRule belongs to one BonusSection
    public function bonusSection()
    {
        return $this->belongsTo(BonusSection::class);
    }

    // Check if rule is met by user
    public function isMetBy($user)
    {
        if (!$user) {
            return false;
        }

        if ($this->rule_type === 'direct_referrals') {
            return $user->directReferrals()->count() >= intval($this->rule_value);
        }

        if ($this->rule_type === 'total_referrals') {
            return $this->getTotalTeamCount($user->id) >= intval($this->rule_value);
        }

        if ($this->rule_type === 'premium_required') {
            return $user->is_premium == true;
        }

        return false;
    }

    // Get total team count recursively
    private function getTotalTeamCount($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return 0;
        }

        $count = $user->directReferrals()->count();

        foreach ($user->directReferrals as $referral) {
            $count += $this->getTotalTeamCount($referral->id);
        }

        return $count;
    }
}