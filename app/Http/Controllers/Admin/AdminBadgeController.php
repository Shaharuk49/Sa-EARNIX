<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaderBadge;
use Illuminate\Http\Request;

class AdminBadgeController extends Controller
{
    public function index()
    {
        $badges = LeaderBadge::orderBy('sort_order')->get();
        return view('admin.badges.index', compact('badges'));
    }

    public function update(Request $request, LeaderBadge $badge)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'condition_text' => 'nullable|string',
            'prize_text'     => 'nullable|string',
            'icon'           => 'nullable|string|max:100',
        ]);

        $badge->update($request->only('name', 'condition_text', 'prize_text', 'icon'));
        return back()->with('success', "Badge '{$badge->name}' updated.");
    }
}
