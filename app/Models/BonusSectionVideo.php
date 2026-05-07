<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusSectionVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'bonus_section_id',
        'title',
        'video_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship: BonusSectionVideo belongs to one BonusSection
    public function section()
    {
        return $this->belongsTo(BonusSection::class, 'bonus_section_id');
    }

    // Relationship: BonusSectionVideo has many BonusVideoProgress
    public function progress()
    {
        return $this->hasMany(BonusVideoProgress::class);
    }

    // Check if user has watched this video
    public function isWatchedBy($userId)
    {
        return $this->progress()->where('user_id', $userId)->exists();
    }
}
