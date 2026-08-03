<?php

namespace App\Http\Controllers\User;

use App\Models\BonusSection;
use App\Models\BonusVideoProgress;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use App\Models\WelcomeBonusClaim;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BonusController extends Controller
{
    /**
     * Display welcome bonus sections
     */
    public function index()
    {
        $user = Auth::user();
        $sections = BonusSection::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Calculate section status for user
        $sectionStatus = $sections->map(function ($section) use ($user) {
            $videoIds = $section->videos()->pluck('id')->toArray();
            $watchedVideoIds = BonusVideoProgress::where('user_id', $user->id)
                ->whereIn('bonus_section_video_id', $videoIds)
                ->pluck('bonus_section_video_id')
                ->toArray();

            return [
                'section' => $section,
                'is_unlocked' => $section->isUnlockedFor($user->id),
                'is_blocked_by_previous' => !$section->hasPreviousSectionsCompletedBy($user->id),
                'total_videos' => count($videoIds),
                'watched_videos' => count($watchedVideoIds),
                'watched_video_ids' => $watchedVideoIds,
                'is_completed' => count($videoIds) > 0 && count($watchedVideoIds) === count($videoIds),
            ];
        });

        // Check if all sections completed
        $all_completed = $sectionStatus->every(fn ($s) => $s['is_completed']);
        $bonus_claimed = WelcomeBonusClaim::where('user_id', $user->id)->exists();

        return view('user.bonus.dashboard', [
            'sections' => $sectionStatus,
            'all_completed' => $all_completed,
            'bonus_claimed' => $bonus_claimed,
            'bonus_amount' => WelcomeBonusClaim::BONUS_AMOUNT,
        ]);
    }

    /**
     * Display single section with videos
     */
    public function section(BonusSection $bonusSection)
    {
        $user = Auth::user();
        
        if (!$bonusSection->isUnlockedFor($user->id)) {
            return redirect()->route('user.bonus')->with('error', 'This section is locked. Complete the required conditions first.');
        }
        
        $videos = $bonusSection->videos()->orderBy('sort_order')->get();
        $watchedProgress = BonusVideoProgress::where('user_id', $user->id)
            ->whereIn('bonus_section_video_id', $videos->pluck('id'))
            ->get(['bonus_section_video_id', 'watched_at']);

        $watchedVideoIds = $watchedProgress->pluck('bonus_section_video_id')->toArray();
        $watchedVideoDates = $watchedProgress->mapWithKeys(function ($item) {
            return [$item->bonus_section_video_id => $item->watched_at];
        })->toArray();
        
        return view('user.bonus.section', [
            'section' => $bonusSection,
            'videos' => $videos,
            'watchedVideoIds' => $watchedVideoIds,
            'watchedVideoDates' => $watchedVideoDates,
        ]);
    }

    /**
     * Mark video as watched
     */
    public function markVideoWatched($videoId)
    {
        $user = Auth::user();
        
        BonusVideoProgress::firstOrCreate([
            'user_id' => $user->id,
            'bonus_section_video_id' => $videoId,
        ], [
            'watched_at' => now(),
        ]);
        
        return response()->json(['success' => true, 'message' => 'Video marked as watched']);
    }

    /**
     * Claim welcome bonus
     */
    public function claim(Request $request)
    {
        $user = Auth::user();
        
        // Check if already claimed
        if (WelcomeBonusClaim::where('user_id', $user->id)->exists()) {
            return redirect()->route('user.bonus')->with('error', 'Bonus already claimed.');
        }
        
        // Check if all sections completed
        $sections = BonusSection::where('is_active', true)->get();
        foreach ($sections as $section) {
            $totalVideos = $section->videos()->count();
            $watchedVideos = BonusVideoProgress::where('user_id', $user->id)
                ->whereIn('bonus_section_video_id', $section->videos()->pluck('id'))
                ->count();
            
            if ($watchedVideos < $totalVideos) {
                return redirect()->route('user.bonus')->with('error', 'Complete all videos in all sections to claim the bonus.');
            }
        }
        
        // Process claim
        DB::transaction(function () use ($user) {
            $bonusAmount = AdminSetting::where('key_name', 'welcome_bonus_amount')->value('value_text');
            $amount = is_numeric($bonusAmount) ? floatval($bonusAmount) : WelcomeBonusClaim::BONUS_AMOUNT;

            $claim = WelcomeBonusClaim::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'claimed_at' => now(),
                'status' => 'claimed',
            ]);

            $walletAccount = WalletAccount::firstOrCreate(
                ['user_id' => $user->id],
                ['current_balance' => 0, 'hold_balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
            );

            $balanceBefore = (float) $walletAccount->current_balance;
            $walletAccount->current_balance = $balanceBefore + $amount;
            $walletAccount->total_earned = (float) $walletAccount->total_earned + $amount;
            $walletAccount->save();

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'source_type' => 'welcome_bonus',
                'reference_type' => WelcomeBonusClaim::class,
                'reference_id' => $claim->id,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceBefore + $amount,
                'status' => 'completed',
                'note' => 'Welcome Bonus Claim - ' . number_format($amount, 2) . ' BDT',
            ]);
        });
        
        return redirect()->route('user.bonus')->with('success', 'Bonus claimed successfully! 1000 BDT added to your wallet.');
    }
}

