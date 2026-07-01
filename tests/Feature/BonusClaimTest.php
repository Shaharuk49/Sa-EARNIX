<?php

use App\Models\BonusSection;
use App\Models\BonusSectionVideo;
use App\Models\BonusVideoProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('credits the wallet when a user claims the welcome bonus', function () {
    $user = User::factory()->create();

    $section = BonusSection::create([
        'title' => 'Intro',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $video = BonusSectionVideo::create([
        'bonus_section_id' => $section->id,
        'title' => 'Welcome video',
        'video_url' => 'https://example.com/video.mp4',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    BonusVideoProgress::create([
        'user_id' => $user->id,
        'bonus_section_video_id' => $video->id,
        'watched_at' => now(),
    ]);

    actingAs($user);

    $response = $this->post(route('user.bonus.claim'));

    $response->assertRedirect(route('user.bonus'));
    $this->assertDatabaseHas('welcome_bonus_claims', [
        'user_id' => $user->id,
        'amount' => 1000,
        'status' => 'claimed',
    ]);
    $this->assertDatabaseHas('wallet_accounts', [
        'user_id' => $user->id,
        'current_balance' => 1000,
    ]);
    $this->assertDatabaseHas('wallet_transactions', [
        'user_id' => $user->id,
        'source_type' => 'welcome_bonus',
        'amount' => 1000,
    ]);
});
