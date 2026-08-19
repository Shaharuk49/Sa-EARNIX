<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCommissionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($request) {
            foreach ($request->input('amounts', []) as $id => $amount) {
                ReferralCommissionSetting::whereKey($id)->update([
                    'amount' => (float) $amount,
                ]);
            }
        });

        return back()->with('success', "Commission rates updated. Total: {$total} BDT");
    }
}
