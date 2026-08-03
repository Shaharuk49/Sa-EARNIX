<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\BonusSection;
use App\Models\BonusSectionVideo;
use App\Models\BonusSectionRule;
use App\Models\WelcomeBonusClaim;
use Illuminate\Http\Request;

class AdminBonusController extends Controller
{
    public function index()
    {
        $sections = BonusSection::with(['videos', 'rules'])->orderBy('sort_order')->get();
        $bonusAmount = AdminSetting::where('key_name', 'welcome_bonus_amount')->value('value_text') ?? WelcomeBonusClaim::BONUS_AMOUNT;
        return view('admin.bonus.index', compact('sections', 'bonusAmount'));
    }

    public function updateBonusAmount(Request $request)
    {
        $request->validate([
            'welcome_bonus_amount' => 'required|numeric|min:0',
        ]);

        AdminSetting::updateOrCreate(
            ['key_name' => 'welcome_bonus_amount'],
            ['value_text' => $request->input('welcome_bonus_amount')]
        );

        return back()->with('success', 'Welcome bonus amount saved.');
    }

    // ── Sections ──
    public function storeSection(Request $request)
    {
        $request->validate(['title' => 'required|string|max:200']);
        $maxOrder = BonusSection::max('sort_order') ?? 0;
        BonusSection::create([
            'title'      => $request->title,
            'sort_order' => $maxOrder + 1,
            'is_active'  => true,
        ]);
        return back()->with('success', 'Section added.');
    }

    public function updateSection(Request $request, BonusSection $section)
    {
        $request->validate(['title' => 'required|string|max:200']);
        $section->update(['title' => $request->title]);
        return back()->with('success', 'Section updated.');
    }

    public function destroySection(BonusSection $section)
    {
        $section->delete();
        return back()->with('success', 'Section deleted.');
    }

    // ── Videos ──
    public function storeVideo(Request $request, BonusSection $section)
    {
        $request->validate([
            'title'     => 'required|string|max:200',
            'video_url' => 'required|url|max:500',
        ]);
        $maxOrder = BonusSectionVideo::where('bonus_section_id', $section->id)->max('sort_order') ?? 0;
        BonusSectionVideo::create([
            'bonus_section_id' => $section->id,
            'title'            => $request->title,
            'video_url'        => $request->video_url,
            'sort_order'       => $maxOrder + 1,
            'is_active'        => true,
        ]);
        return back()->with('success', 'Video added.');
    }

    public function destroyVideo(BonusSectionVideo $video)
    {
        $video->delete();
        return back()->with('success', 'Video removed.');
    }

    // ── Rules ──
    public function storeRule(Request $request, BonusSection $section)
    {
        $request->validate([
            'rule_type'  => 'required|in:direct_referrals,total_referrals,premium_required',
            'rule_value' => 'nullable|string|max:500',
        ]);

        $ruleValue = $request->rule_type === 'premium_required'
            ? 'required'
            : $request->rule_value;

        if ($request->rule_type !== 'premium_required' && empty($ruleValue)) {
            return back()->withErrors(['rule_value' => 'Rule value is required for referral rules.']);
        }

        BonusSectionRule::create([
            'bonus_section_id' => $section->id,
            'rule_type'        => $request->rule_type,
            'rule_value'       => $ruleValue,
        ]);

        return back()->with('success', 'Rule added.');
    }

    public function destroyRule(BonusSectionRule $rule)
    {
        $rule->delete();
        return back()->with('success', 'Rule removed.');
    }

    public function saveAll(Request $request)
    {
        $request->validate([
            'welcome_bonus_amount' => 'required|numeric|min:0',
            'new_section_title' => 'nullable|string|max:200',
            'video_titles' => 'nullable|array',
            'video_urls' => 'nullable|array',
            'rule_types' => 'nullable|array',
            'rule_values' => 'nullable|array',
        ]);

        AdminSetting::updateOrCreate(
            ['key_name' => 'welcome_bonus_amount'],
            ['value_text' => $request->input('welcome_bonus_amount')]
        );

        if ($request->filled('new_section_title')) {
            $maxOrder = BonusSection::max('sort_order') ?? 0;
            BonusSection::create([
                'title' => $request->input('new_section_title'),
                'sort_order' => $maxOrder + 1,
                'is_active' => true,
            ]);
        }

        $videoTitles = $request->input('video_titles', []);
        $videoUrls = $request->input('video_urls', []);
        foreach ($videoTitles as $sectionId => $title) {
            $url = urldecode($videoUrls[$sectionId] ?? '');
            $title = urldecode($title);
            if (filled($title) && filled($url)) {
                $section = BonusSection::find($sectionId);
                if ($section) {
                    $maxOrder = BonusSectionVideo::where('bonus_section_id', $section->id)->max('sort_order') ?? 0;
                    BonusSectionVideo::create([
                        'bonus_section_id' => $section->id,
                        'title' => $title,
                        'video_url' => $url,
                        'sort_order' => $maxOrder + 1,
                        'is_active' => true,
                    ]);
                }
            }
        }

        $ruleTypes = $request->input('rule_types', []);
        $ruleValues = $request->input('rule_values', []);
        foreach ($ruleTypes as $sectionId => $type) {
            $value = $ruleValues[$sectionId] ?? null;
            $section = BonusSection::find($sectionId);
            if ($section && in_array($type, ['direct_referrals', 'total_referrals', 'premium_required'])) {
                $ruleValue = $type === 'premium_required' ? 'required' : $value;
                if ($type !== 'premium_required' && empty($ruleValue)) {
                    continue;
                }
                BonusSectionRule::create([
                    'bonus_section_id' => $section->id,
                    'rule_type' => $type,
                    'rule_value' => $ruleValue,
                ]);
            }
        }

        return back()->with('success', 'All bonus data saved.');
    }
}
