<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\AdminSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.user', function ($view) {
            try {
                $settings = AdminSetting::whereIn('key_name', [
                    'support_link',
                    'dropshipping_link',
                    'laptop_apply_link',
                ])->pluck('value_text', 'key_name');

                $view->with([
                    'supportLink'      => $settings['support_link'] ?? null,
                    'dropshippingLink' => $settings['dropshipping_link'] ?? null,
                    'laptopApplyLink'  => $settings['laptop_apply_link'] ?? null,
                ]);
            } catch (\Exception $e) {
                $view->with([
                    'supportLink'      => null,
                    'dropshippingLink' => null,
                    'laptopApplyLink'  => null,
                ]);
            }
        });
    }
}
