<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCommissionSetting;
use Illuminate\Http\Request;

class AdminCommissionController extends Controller
{
    public function index()
    {
        $commissions = ReferralCommissionSetting::orderBy('generation_number')->get();
        $total = $commissions->sum('amount');
        return view('admin.commissions.index', compact('commissions', 'total'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'amounts'   => 'required|array',
            'amounts.*' => 'required|numeric|min:0',
        ]);

        $total = array_sum($request->amounts);
        if ($total > 220) {
            return back()->with('error', "Total cannot exceed 220 BDT. Current total: {$total} BDT");
        }

        foreach ($request->amounts as $id => $amount) {
            ReferralCommissionSetting::where('id', $id)->update(['amount' => $amount]);
        }

        return back()->with('success', "Commission rates updated. Total: {$total} BDT");
    }
}
