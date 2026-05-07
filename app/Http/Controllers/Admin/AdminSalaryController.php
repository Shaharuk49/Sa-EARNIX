<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlySalaryLevel;
use App\Models\MonthlySalaryRule;
use Illuminate\Http\Request;

class AdminSalaryController extends Controller
{
    public function index()
    {
        $levels = MonthlySalaryLevel::with('rules')->orderBy('level_number')->get();
        return view('admin.salary.index', compact('levels'));
    }

    public function toggleLevel(MonthlySalaryLevel $level)
    {
        $level->update(['is_active_by_admin' => !$level->is_active_by_admin]);
        $status = $level->is_active_by_admin ? 'activated' : 'deactivated';
        return back()->with('success', "Level {$level->level_number} {$status}.");
    }

    public function storeRule(Request $request, MonthlySalaryLevel $level)
    {
        $request->validate(['rule_text' => 'required|string|max:500']);
        $maxOrder = MonthlySalaryRule::where('monthly_salary_level_id', $level->id)->max('sort_order') ?? 0;
        MonthlySalaryRule::create([
            'monthly_salary_level_id' => $level->id,
            'rule_text'               => $request->rule_text,
            'sort_order'              => $maxOrder + 1,
        ]);
        return back()->with('success', 'Rule added.');
    }

    public function updateRule(Request $request, MonthlySalaryRule $rule)
    {
        $request->validate(['rule_text' => 'required|string|max:500']);
        $rule->update(['rule_text' => $request->rule_text]);
        return back()->with('success', 'Rule updated.');
    }

    public function destroyRule(MonthlySalaryRule $rule)
    {
        $rule->delete();
        return back()->with('success', 'Rule removed.');
    }
}
