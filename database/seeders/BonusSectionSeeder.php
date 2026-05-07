<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BonusSection;
use App\Models\BonusSectionRule;
use App\Models\BonusSectionVideo;

class BonusSectionSeeder extends Seeder
{
    public function run(): void
    {
        // Section 1: Getting Started (2+ direct referrals + Premium)
        $section1 = BonusSection::updateOrCreate(
            ['section_number' => 1],
            [
                'title' => 'Getting Started',
                'description' => 'Learn the basics of SA EarniX platform',
                'bonus_amount' => 1000,
                'is_active' => true,
            ]
        );

        BonusSectionRule::updateOrCreate(
            ['bonus_section_id' => $section1->id, 'rule_type' => 'direct_referrals'],
            ['rule_value' => 2]
        );

        BonusSectionRule::updateOrCreate(
            ['bonus_section_id' => $section1->id, 'rule_type' => 'is_premium'],
            ['rule_value' => 1]
        );

        BonusSectionVideo::updateOrCreate(
            ['bonus_section_id' => $section1->id, 'video_order' => 1],
            [
                'video_title' => 'Introduction to SA EarniX',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration_minutes' => 5,
                'description' => 'Learn about SA EarniX platform features and how to get started.',
            ]
        );

        BonusSectionVideo::updateOrCreate(
            ['bonus_section_id' => $section1->id, 'video_order' => 2],
            [
                'video_title' => 'Your First Steps',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration_minutes' => 8,
                'description' => 'Set up your profile and start referring friends.',
            ]
        );

        // Section 2: Building Your Team (12+ total referrals)
        $section2 = BonusSection::updateOrCreate(
            ['section_number' => 2],
            [
                'title' => 'Building Your Team',
                'description' => 'Strategies to grow your network',
                'bonus_amount' => 1000,
                'is_active' => true,
            ]
        );

        BonusSectionRule::updateOrCreate(
            ['bonus_section_id' => $section2->id, 'rule_type' => 'total_referrals'],
            ['rule_value' => 12]
        );

        BonusSectionVideo::updateOrCreate(
            ['bonus_section_id' => $section2->id, 'video_order' => 1],
            [
                'video_title' => 'Effective Referral Strategies',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration_minutes' => 10,
                'description' => 'Learn proven strategies to recruit more members.',
            ]
        );

        BonusSectionVideo::updateOrCreate(
            ['bonus_section_id' => $section2->id, 'video_order' => 2],
            [
                'video_title' => 'Commission Structure Explained',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration_minutes' => 7,
                'description' => 'Understand how you earn from your network.',
            ]
        );

        // Section 3: Advanced Growth (25+ total referrals)
        $section3 = BonusSection::updateOrCreate(
            ['section_number' => 3],
            [
                'title' => 'Advanced Growth',
                'description' => 'Advanced techniques to maximize your earnings',
                'bonus_amount' => 1000,
                'is_active' => true,
            ]
        );

        BonusSectionRule::updateOrCreate(
            ['bonus_section_id' => $section3->id, 'rule_type' => 'total_referrals'],
            ['rule_value' => 25]
        );

        BonusSectionVideo::updateOrCreate(
            ['bonus_section_id' => $section3->id, 'video_order' => 1],
            [
                'video_title' => 'Building a Strong Organization',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration_minutes' => 12,
                'description' => 'Create a sustainable organization with multiple levels.',
            ]
        );

        BonusSectionVideo::updateOrCreate(
            ['bonus_section_id' => $section3->id, 'video_order' => 2],
            [
                'video_title' => 'Scaling Your Business',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duration_minutes' => 15,
                'description' => 'Strategies for exponential growth and maximum earnings.',
            ]
        );
    }
}