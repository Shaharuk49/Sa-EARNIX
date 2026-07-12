<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\WithdrawMethod;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = AdminSetting::pluck('value_text', 'key_name');
        $withdrawMethods = WithdrawMethod::orderBy('name')->get();
        return view('admin.settings.index', compact('settings', 'withdrawMethods'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'support_link'                => 'nullable|string|max:500',
            'laptop_apply_link'           => 'nullable|string|max:500',
            'dropshipping_link'           => 'nullable|string|max:500',
            'monthly_salary_rules'        => 'nullable|string',
            'registration_payment_phone'  => 'nullable|string|max:20',
            'premium_upgrade_payment_phone' => 'nullable|string|max:20',
            'premium_upgrade_amount'      => 'nullable|numeric|min:0',
        ]);

        $keys = [
            'support_link',
            'laptop_apply_link',
            'dropshipping_link',
            'monthly_salary_rules',
            'registration_payment_phone',
            'premium_upgrade_payment_phone',
            'premium_upgrade_amount',
        ];
        foreach ($keys as $key) {
            AdminSetting::updateOrCreate(
                ['key_name' => $key],
                ['value_text' => $request->input($key, '')]
            );
        }

        return back()->with('success', 'Settings saved.');
    }

    // ── Withdraw Methods ──
    public function storeMethod(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:withdraw_methods,name']);
        WithdrawMethod::create(['name' => $request->name, 'is_active' => true]);
        return back()->with('success', 'Withdraw method added.');
    }

    public function toggleMethod(WithdrawMethod $method)
    {
        $method->update(['is_active' => !$method->is_active]);
        return back()->with('success', 'Method updated.');
    }

    public function destroyMethod(WithdrawMethod $method)
    {
        $method->delete();
        return back()->with('success', 'Method removed.');
    }
}
