<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusSection;
use App\Models\BonusSectionVideo;
use App\Models\BonusSectionRule;
use Illuminate\Http\Request;

class AdminBonusController extends Controller
{
    public function index()
    {
        $sections = BonusSection::with(['videos', 'rules'])->orderBy('sort_order')->get();
        return view('admin.bonus.index', compact('sections'));
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
            'rule_type'  => 'required|string|max:100',
            'rule_value' => 'required|string|max:500',
        ]);
        BonusSectionRule::create([
            'bonus_section_id' => $section->id,
            'rule_type'        => $request->rule_type,
            'rule_value'       => $request->rule_value,
        ]);
        return back()->with('success', 'Rule added.');
    }

    public function destroyRule(BonusSectionRule $rule)
    {
        $rule->delete();
        return back()->with('success', 'Rule removed.');
    }
}
