<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCommissionController;
use App\Http\Controllers\Admin\AdminSalaryController;
use App\Http\Controllers\Admin\AdminBadgeController;
use App\Http\Controllers\Admin\AdminBonusController;
use App\Http\Controllers\Admin\AdminWithdrawController;
use App\Http\Controllers\Admin\AdminOfficialLinksController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminPremiumController;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'storeRegistration'])->name('register.store');

    Route::get('register/payment', [RegisterController::class, 'showPaymentPage'])->name('payment.show');
    Route::post('register/payment', [RegisterController::class, 'processPayment'])->name('payment.process');
});

// Registration pending approval page (no auth needed)
Route::get('register/pending', fn() => view('auth.registration-pending'))->name('registration.pending');

Route::middleware('auth')->group(function () {
    Route::get('home', [App\Http\Controllers\User\HomeController::class, 'index'])->name('user.home');
    Route::get('personal-info', [App\Http\Controllers\User\HomeController::class, 'personalInfo'])->name('user.personal-info');
    Route::post('personal-info', [App\Http\Controllers\User\HomeController::class, 'updatePersonalInfo'])->name('user.personal-info.update');
    
    Route::prefix('team')->group(function () {
        Route::get('/', [App\Http\Controllers\User\TeamController::class, 'index'])->name('user.team');
        Route::get('partners', [App\Http\Controllers\User\TeamController::class, 'partners'])->name('user.team.partners');
    });
    
    Route::prefix('income')->group(function () {
        Route::get('/', [App\Http\Controllers\User\IncomeController::class, 'index'])->name('user.income');
        Route::get('history', [App\Http\Controllers\User\IncomeController::class, 'history'])->name('user.income.history');
    });

    Route::prefix('premium')->group(function () {
    Route::get('upgrade/status/{premium}', [App\Http\Controllers\User\PremiumUpgradeController::class, 'status'])
    ->name('premium.upgrade.status');
    Route::get('upgrade', [App\Http\Controllers\User\PremiumUpgradeController::class, 'show'])
        ->name('premium.upgrade.show');

    Route::post('upgrade', [App\Http\Controllers\User\PremiumUpgradeController::class, 'process'])
        ->name('premium.upgrade.process');

    Route::get('upgrade/success/{premium}', [App\Http\Controllers\User\PremiumUpgradeController::class, 'success'])
        ->name('premium.upgrade.success');

    Route::get('benefits', [App\Http\Controllers\User\PremiumUpgradeController::class, 'benefits'])
        ->name('premium.benefits');

    Route::get('payment', [App\Http\Controllers\User\PremiumUpgradeController::class, 'showPaymentPage'])
        ->name('premium.payment.show');

    Route::post('payment', [App\Http\Controllers\User\PremiumUpgradeController::class, 'processPayment'])
        ->name('premium.payment.process');

    Route::get('payment/success', [App\Http\Controllers\User\PremiumUpgradeController::class, 'paymentSuccess'])
        ->name('premium.payment.success');
});

    Route::prefix('bonus')->group(function () {
        Route::get('/', [App\Http\Controllers\User\BonusController::class, 'index'])->name('user.bonus');
        Route::get('section/{bonusSection}', [App\Http\Controllers\User\BonusController::class, 'section'])->name('user.bonus.section');
        Route::post('video/{video}/watched', [App\Http\Controllers\User\BonusController::class, 'markVideoWatched'])->name('user.bonus.video.watched');
        Route::post('claim', [App\Http\Controllers\User\BonusController::class, 'claim'])->name('user.bonus.claim');
    });

    Route::prefix('salary')->group(function () {
        Route::get('/', [App\Http\Controllers\User\MonthlySalaryController::class, 'index'])->name('user.salary');
        Route::post('claim', [App\Http\Controllers\User\MonthlySalaryController::class, 'claim'])->name('user.salary.claim');
    });

    Route::prefix('badge')->group(function () {
        Route::get('/', [App\Http\Controllers\User\LeaderBadgeController::class, 'index'])->name('user.badge.index');
        Route::get('{leaderBadge:slug}', [App\Http\Controllers\User\LeaderBadgeController::class, 'show'])->name('user.badge.show');
    });

    Route::prefix('leaderboard')->group(function () {
        Route::get('/', [App\Http\Controllers\User\LeaderboardController::class, 'index'])->name('user.leaderboard');
    });

    Route::prefix('withdraw')->group(function () {
        Route::get('/', [App\Http\Controllers\User\WithdrawController::class, 'index'])->name('user.withdraw');
        Route::post('process', [App\Http\Controllers\User\WithdrawController::class, 'process'])->name('user.withdraw.process');
    });

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    // Freelancing
    Route::prefix('freelancing')->group(function () {
        Route::get('/', [App\Http\Controllers\User\FreelancingController::class, 'index'])->name('user.freelancing');
    });

    // Skills
    Route::prefix('skills')->group(function () {
        Route::get('/', [App\Http\Controllers\User\SkillsController::class, 'index'])->name('user.skills.index');
        Route::get('{course}', [App\Http\Controllers\User\SkillsController::class, 'show'])->name('user.skills.show');
        Route::post('video/{video}/watched', [App\Http\Controllers\User\SkillsController::class, 'markWatched'])->name('user.skills.video.watched');
    });

    // Shop (E-Commerce / Dropshipping)
    Route::prefix('shop')->group(function () {
        Route::get('/', [App\Http\Controllers\User\ShopController::class, 'index'])->name('user.shop.index');
        Route::get('orders', [App\Http\Controllers\User\ShopController::class, 'orders'])->name('user.orders.index');
        Route::get('{product}', [App\Http\Controllers\User\ShopController::class, 'show'])->name('user.shop.show');
        Route::post('{product}/order', [App\Http\Controllers\User\ShopController::class, 'placeOrder'])->name('user.shop.order');
    });
});

Route::get('/', function () {
    return redirect()->route('login');
});

// ═══════════════════════════════════════
// ADMIN ROUTES
// ═══════════════════════════════════════
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest-only (not logged in as admin)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated admin routes
    Route::middleware('admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('users', [AdminDashboardController::class, 'users'])->name('users.index');
        Route::get('users/{user}', [AdminDashboardController::class, 'showUser'])->name('users.show');

        // Commission (24-gen)
        Route::get('commissions', [AdminCommissionController::class, 'index'])->name('commissions.index');
        Route::post('commissions', [AdminCommissionController::class, 'update'])->name('commissions.update');

        // Monthly Salary Levels
        Route::get('salary', [AdminSalaryController::class, 'index'])->name('salary.index');
        Route::post('salary/{level}/toggle', [AdminSalaryController::class, 'toggleLevel'])->name('salary.toggle');
        Route::post('salary/{level}/rules', [AdminSalaryController::class, 'storeRule'])->name('salary.rule.store');
        Route::put('salary/rules/{rule}', [AdminSalaryController::class, 'updateRule'])->name('salary.rule.update');
        Route::delete('salary/rules/{rule}', [AdminSalaryController::class, 'destroyRule'])->name('salary.rule.destroy');

        // Leader Badges
        Route::get('badges', [AdminBadgeController::class, 'index'])->name('badges.index');
        Route::put('badges/{badge}', [AdminBadgeController::class, 'update'])->name('badges.update');

        // Bonus Sections & Videos
        Route::get('bonus', [AdminBonusController::class, 'index'])->name('bonus.index');
        Route::post('bonus/sections', [AdminBonusController::class, 'storeSection'])->name('bonus.section.store');
        Route::put('bonus/sections/{section}', [AdminBonusController::class, 'updateSection'])->name('bonus.section.update');
        Route::delete('bonus/sections/{section}', [AdminBonusController::class, 'destroySection'])->name('bonus.section.destroy');
        Route::post('bonus/sections/{section}/videos', [AdminBonusController::class, 'storeVideo'])->name('bonus.video.store');
        Route::delete('bonus/videos/{video}', [AdminBonusController::class, 'destroyVideo'])->name('bonus.video.destroy');
        Route::post('bonus/sections/{section}/rules', [AdminBonusController::class, 'storeRule'])->name('bonus.rule.store');
        Route::delete('bonus/rules/{rule}', [AdminBonusController::class, 'destroyRule'])->name('bonus.rule.destroy');

        // Withdraw Management
        Route::get('withdraw', [AdminWithdrawController::class, 'index'])->name('withdraw.index');
        Route::post('withdraw/{withdraw}/approve', [AdminWithdrawController::class, 'approve'])->name('withdraw.approve');
        Route::post('withdraw/{withdraw}/reject', [AdminWithdrawController::class, 'reject'])->name('withdraw.reject');

        // Official Links
        Route::get('official-links', [AdminOfficialLinksController::class, 'index'])->name('official-links.index');
        Route::post('official-links', [AdminOfficialLinksController::class, 'store'])->name('official-links.store');
        Route::put('official-links/{officialLink}', [AdminOfficialLinksController::class, 'update'])->name('official-links.update');
        Route::delete('official-links/{officialLink}', [AdminOfficialLinksController::class, 'destroy'])->name('official-links.destroy');

        // Withdraw Methods + Site Settings
        Route::get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/methods', [AdminSettingsController::class, 'storeMethod'])->name('withdraw-methods.store');
        Route::post('settings/methods/{method}/toggle', [AdminSettingsController::class, 'toggleMethod'])->name('withdraw-methods.toggle');
        Route::delete('settings/methods/{method}', [AdminSettingsController::class, 'destroyMethod'])->name('withdraw-methods.destroy');

        // Registration Payments
        Route::get('registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
        Route::post('registrations/{registration}/approve', [AdminRegistrationController::class, 'approve'])->name('registrations.approve');
        Route::post('registrations/{registration}/reject', [AdminRegistrationController::class, 'reject'])->name('registrations.reject');

        // Alias for navbar
        Route::get('withdraw-methods', fn() => redirect()->route('admin.settings.index'))->name('withdraw-methods.index');
        Route::get('payments', fn() => redirect()->route('admin.registrations.index'))->name('payments.index');

           // Premium Upgrade Payments
        Route::get('premium', [AdminPremiumController::class, 'index'])->name('premium.index');
        Route::post('premium/{premium}/approve', [AdminPremiumController::class, 'approve'])->name('premium.approve');
        Route::post('premium/{premium}/reject', [AdminPremiumController::class, 'reject'])->name('premium.reject');
        
      
    
    });
});

