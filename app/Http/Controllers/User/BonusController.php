<?php

namespace App\Http\Controllers\User;

use App\Models\BonusSection;
use App\Models\BonusVideoProgress;
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
        $sections = BonusSection::where('is_active', true)->orderBy('sort_order')->get();
        
        // Calculate section status for user
        $sectionStatus = $sections->map(function ($section) use ($user) {
            return [
                'section' => $section,
                'is_unlocked' => $section->isUnlockedFor($user->id),
                'total_videos' => $section->videos()->count(),
                'watched_videos' => BonusVideoProgress::where('user_id', $user->id)
                    ->whereIn('bonus_section_video_id', $section->videos()->pluck('id'))
                    ->count(),
            ];
        });
        
        // Check if all sections completed
        $all_completed = $sectionStatus->every(fn($s) => $s['watched_videos'] == $s['total_videos']);
        $bonus_claimed = WelcomeBonusClaim::where('user_id', $user->id)->exists();
        
        return view('user.bonus.dashboard', [
            'sections' => $sectionStatus,
            'all_completed' => $all_completed,
            'bonus_claimed' => $bonus_claimed,
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
        $watchedVideoIds = BonusVideoProgress::where('user_id', $user->id)
            ->whereIn('bonus_section_video_id', $videos->pluck('id'))
            ->pluck('bonus_section_video_id')
            ->toArray();
        
        return view('user.bonus.section', [
            'section' => $bonusSection,
            'videos' => $videos,
            'watchedVideoIds' => $watchedVideoIds,
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
            $amount = WelcomeBonusClaim::BONUS_AMOUNT;
            
            // Create claim record
            WelcomeBonusClaim::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'claimed_at' => now(),
                'status' => 'completed',
            ]);
            
            // Add to wallet
            $walletAccount = $user->walletAccount();
            if ($walletAccount) {
                $walletAccount->increment('current_balance', $amount);
                
                // Log transaction
                $walletAccount->transactions()->create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'type' => 'credit',
                    'source_type' => 'welcome_bonus',
                    'description' => 'Welcome Bonus Claim - 1000 BDT',
                    'balance_before' => $walletAccount->current_balance - $amount,
                    'balance_after' => $walletAccount->current_balance,
                    'status' => 'completed',
                    'transaction_date' => now(),
                ]);
            }
        });
        
        return redirect()->route('user.bonus')->with('success', 'Bonus claimed successfully! 1000 BDT added to your wallet.');
    }
}

