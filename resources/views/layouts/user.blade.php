<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SA EarniX')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @yield('styles')
</head>
<body class="page-shell user-shell"
      data-flash-success="{{ session('success') }}"
      data-flash-error="{{ session('error') }}">

    {{-- ══════════════════════════════════
         DESKTOP SIDEBAR (d-none d-lg-flex)
    ══════════════════════════════════ --}}
    <aside class="user-sidebar d-none d-lg-flex flex-column">

        {{-- Brand --}}
        <div class="user-sidebar-brand">
            <div class="d-flex align-items-center gap-2">
                <div class="user-brand-badge">
                    <i class="fas fa-network-wired"></i>
                </div>
                <div>
                    <p class="user-brand-title mb-0">SA EarniX</p>
                    <p class="user-brand-sub mb-0">Member Panel</p>
                </div>
            </div>
        </div>

        {{-- User info --}}
        <div class="user-sidebar-user">
            <div class="d-flex align-items-center gap-2">
                <div class="user-avatar-circle">
                    <i class="fas fa-user small"></i>
                </div>
                <div class="min-w-0">
                    <p class="mb-0 fw-bold text-white small text-truncate" style="max-width:130px">{{ auth()->user()->name ?? 'Member' }}</p>
                    <p class="mb-0" style="font-size:11px;color:#93a8cc">{{ auth()->user()->affiliate_id ?? '' }}</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="user-sidebar-nav flex-grow-1 overflow-auto">

            {{-- Main --}}
            <div class="user-nav-section-label">Main</div>
            <a href="{{ route('user.home') }}" class="user-nav-link {{ request()->routeIs('user.home') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-home"></i></span>
                <span class="user-nav-title">Home</span>
            </a>
            <a href="{{ route('user.team') }}" class="user-nav-link {{ request()->routeIs('user.team*') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-users"></i></span>
                <span class="user-nav-title">My Team</span>
            </a>
            <a href="{{ route('user.income') }}" class="user-nav-link {{ request()->routeIs('user.income*') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-wallet"></i></span>
                <span class="user-nav-title">Income</span>
            </a>
            <a href="{{ route('user.withdraw') }}" class="user-nav-link {{ request()->routeIs('user.withdraw*') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-arrow-up-from-bracket"></i></span>
                <span class="user-nav-title">Withdraw</span>
            </a>

            {{-- Earnings --}}
            <div class="user-nav-section-label mt-2">Earnings</div>
            <a href="{{ route('user.bonus') }}" class="user-nav-link {{ request()->routeIs('user.bonus*') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-gift"></i></span>
                <span class="user-nav-title">Welcome Bonus</span>
            </a>
            <a href="{{ route('user.salary') }}" class="user-nav-link {{ request()->routeIs('user.salary*') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-money-bill-wave"></i></span>
                <span class="user-nav-title">Monthly Salary</span>
            </a>
            <a href="{{ route('premium.upgrade.show') }}" class="user-nav-link {{ request()->routeIs('premium.*') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-star"></i></span>
                <span class="user-nav-title">Premium Upgrade</span>
            </a>

            {{-- Ranks --}}
            <div class="user-nav-section-label mt-2">Ranks</div>
            <a href="{{ route('user.badge.index') }}" class="user-nav-link {{ request()->routeIs('user.badge*') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-medal"></i></span>
                <span class="user-nav-title">Leader Ranks</span>
            </a>
            <a href="{{ route('user.leaderboard') }}" class="user-nav-link {{ request()->routeIs('user.leaderboard') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-trophy"></i></span>
                <span class="user-nav-title">Leaderboard</span>
            </a>

            {{-- More Ways to Earn --}}
            <div class="user-nav-section-label mt-2">Earn More</div>
            <a href="{{ route('user.freelancing') }}" class="user-nav-link {{ request()->routeIs('user.freelancing') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-laptop-code"></i></span>
                <span class="user-nav-title">Freelancing</span>
            </a>
            <a href="{{ route('user.skills.index') }}" class="user-nav-link {{ request()->routeIs('user.skills*') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-graduation-cap"></i></span>
                <span class="user-nav-title">Skills Learning</span>
            </a>
            <a href="{{ route('user.shop.index') }}" class="user-nav-link {{ request()->routeIs('user.shop*') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-store"></i></span>
                <span class="user-nav-title">Shop</span>
            </a>
            <a href="{{ route('user.orders.index') }}" class="user-nav-link {{ request()->routeIs('user.orders*') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-box"></i></span>
                <span class="user-nav-title">My Orders</span>
            </a>
            @if($dropshippingLink)
            <a href="{{ $dropshippingLink }}" target="_blank" rel="noopener noreferrer" class="user-nav-link">
                <span class="user-nav-icon"><i class="fas fa-box-open"></i></span>
                <span class="user-nav-title">Dropshipping</span>
                <i class="fas fa-external-link-alt ms-auto" style="font-size:10px;color:#6880ab"></i>
            </a>
            @endif
            @if($laptopApplyLink)
            <a href="{{ $laptopApplyLink }}" target="_blank" rel="noopener noreferrer" class="user-nav-link">
                <span class="user-nav-icon"><i class="fas fa-laptop"></i></span>
                <span class="user-nav-title">PC/Laptop Apply</span>
                <i class="fas fa-external-link-alt ms-auto" style="font-size:10px;color:#6880ab"></i>
            </a>
            @endif

            {{-- Account --}}
            <div class="user-nav-section-label mt-2">Account</div>
            <a href="{{ route('user.personal-info') }}" class="user-nav-link {{ request()->routeIs('user.personal-info') ? 'active' : '' }}">
                <span class="user-nav-icon"><i class="fas fa-user"></i></span>
                <span class="user-nav-title">My Profile</span>
            </a>
            @if($supportLink)
            <a href="{{ $supportLink }}" target="_blank" rel="noopener noreferrer" class="user-nav-link">
                <span class="user-nav-icon"><i class="fas fa-headset"></i></span>
                <span class="user-nav-title">Support</span>
                <i class="fas fa-external-link-alt ms-auto" style="font-size:10px;color:#6880ab"></i>
            </a>
            @endif

        </nav>

        {{-- Logout --}}
        <div class="user-sidebar-footer">
            <a href="{{ route('logout') }}" class="user-logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>

    </aside>

    {{-- ══════════════════════════════════
         MAIN WRAPPER
    ══════════════════════════════════ --}}
    <div class="user-main-wrap">

        {{-- Mobile topbar --}}
        <header class="user-topbar d-lg-none">
            <a class="user-topbar-brand" href="{{ route('user.home') }}">
                <i class="fas fa-network-wired me-2"></i>SA EarniX
            </a>
            <div class="d-flex align-items-center gap-2">
                <span class="small text-muted">{{ auth()->user()->name ?? '' }}</span>
                <button class="btn btn-sm btn-outline-secondary rounded-pill"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#mobileMenu"
                        aria-controls="mobileMenu"
                        aria-label="Open menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="user-page-content pb-5 pb-lg-0">
            @yield('content')
        </main>

    </div>

    {{-- ══════════════════════════════════
         MOBILE BOTTOM NAV
    ══════════════════════════════════ --}}
    <div class="bottom-nav d-lg-none">
        <div class="d-flex justify-content-around w-100">
            <a href="{{ route('user.home') }}" class="bottom-nav-item {{ request()->routeIs('user.home') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Home</span>
            </a>
            <a href="{{ route('user.team') }}" class="bottom-nav-item {{ request()->routeIs('user.team*') ? 'active' : '' }}">
                <i class="fas fa-users"></i><span>Team</span>
            </a>
            <a href="{{ route('user.income') }}" class="bottom-nav-item {{ request()->routeIs('user.income*') ? 'active' : '' }}">
                <i class="fas fa-wallet"></i><span>Income</span>
            </a>
            <a href="{{ route('user.withdraw') }}" class="bottom-nav-item {{ request()->routeIs('user.withdraw*') ? 'active' : '' }}">
                <i class="fas fa-arrow-up-from-bracket"></i><span>Withdraw</span>
            </a>
            <button type="button" class="bottom-nav-item btn btn-link p-0 border-0 {{ request()->routeIs('user.bonus*','user.salary*','user.badge*','user.leaderboard','premium.*','user.personal-info','user.freelancing','user.skills*','user.shop*','user.orders*') ? 'active' : '' }}"
               data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Open menu">
                <i class="fas fa-bars"></i><span>Menu</span>
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════
         MOBILE OFFCANVAS MENU
    ══════════════════════════════════ --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" style="width:280px;max-width:85vw">
        <div class="offcanvas-header border-0" style="background:linear-gradient(135deg,#212529,#0d6efd)">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-network-wired text-white"></i>
                <span class="fw-bold text-white">SA EarniX</span>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0" style="overflow-y:auto">

            {{-- User strip --}}
            <div class="d-flex align-items-center gap-2 px-3 py-3 border-bottom" style="background:#f8f9fa">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:36px;height:36px">
                    <i class="fas fa-user text-white small"></i>
                </div>
                <div class="min-w-0">
                    <p class="mb-0 fw-bold small text-truncate">{{ auth()->user()->name ?? 'Member' }}</p>
                    <p class="mb-0 text-muted" style="font-size:11px">{{ auth()->user()->affiliate_id ?? '' }}</p>
                </div>
            </div>

            <ul class="list-unstyled mb-0">
                {{-- Main --}}
                <li class="mobile-nav-section">Main</li>
                <li><a href="{{ route('user.home') }}" class="mobile-nav-link {{ request()->routeIs('user.home') ? 'active' : '' }}">
                    <i class="fas fa-home fa-fw"></i> Home</a></li>
                <li><a href="{{ route('user.team') }}" class="mobile-nav-link {{ request()->routeIs('user.team*') ? 'active' : '' }}">
                    <i class="fas fa-users fa-fw"></i> My Team</a></li>
                <li><a href="{{ route('user.income') }}" class="mobile-nav-link {{ request()->routeIs('user.income*') ? 'active' : '' }}">
                    <i class="fas fa-wallet fa-fw"></i> Income</a></li>
                <li><a href="{{ route('user.withdraw') }}" class="mobile-nav-link {{ request()->routeIs('user.withdraw*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-up-from-bracket fa-fw"></i> Withdraw</a></li>

                {{-- Earnings --}}
                <li class="mobile-nav-section">Earnings</li>
                <li><a href="{{ route('user.bonus') }}" class="mobile-nav-link {{ request()->routeIs('user.bonus*') ? 'active' : '' }}">
                    <i class="fas fa-gift fa-fw text-warning"></i> Welcome Bonus</a></li>
                <li><a href="{{ route('user.salary') }}" class="mobile-nav-link {{ request()->routeIs('user.salary*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave fa-fw text-success"></i> Monthly Salary</a></li>
                <li><a href="{{ route('premium.upgrade.show') }}" class="mobile-nav-link {{ request()->routeIs('premium.*') ? 'active' : '' }}">
                    <i class="fas fa-star fa-fw text-warning"></i> Premium Upgrade</a></li>

                {{-- Ranks --}}
                <li class="mobile-nav-section">Ranks</li>
                <li><a href="{{ route('user.badge.index') }}" class="mobile-nav-link {{ request()->routeIs('user.badge*') ? 'active' : '' }}">
                    <i class="fas fa-medal fa-fw text-primary"></i> Leader Ranks</a></li>
                <li><a href="{{ route('user.leaderboard') }}" class="mobile-nav-link {{ request()->routeIs('user.leaderboard') ? 'active' : '' }}">
                    <i class="fas fa-trophy fa-fw text-warning"></i> Leaderboard</a></li>

                {{-- Earn More --}}
                <li class="mobile-nav-section">Earn More</li>
                <li><a href="{{ route('user.freelancing') }}" class="mobile-nav-link {{ request()->routeIs('user.freelancing') ? 'active' : '' }}">
                    <i class="fas fa-laptop-code fa-fw text-primary"></i> Freelancing</a></li>
                <li><a href="{{ route('user.skills.index') }}" class="mobile-nav-link {{ request()->routeIs('user.skills*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap fa-fw text-success"></i> Skills Learning</a></li>
                <li><a href="{{ route('user.shop.index') }}" class="mobile-nav-link {{ request()->routeIs('user.shop*') ? 'active' : '' }}">
                    <i class="fas fa-store fa-fw text-warning"></i> Shop</a></li>
                <li><a href="{{ route('user.orders.index') }}" class="mobile-nav-link {{ request()->routeIs('user.orders*') ? 'active' : '' }}">
                    <i class="fas fa-box fa-fw text-info"></i> My Orders</a></li>
                @if($dropshippingLink)
                <li><a href="{{ $dropshippingLink }}" target="_blank" rel="noopener noreferrer" class="mobile-nav-link">
                    <i class="fas fa-box-open fa-fw text-info"></i> Dropshipping &amp; POD <i class="fas fa-external-link-alt ms-1 small text-muted"></i></a></li>
                @endif
                @if($laptopApplyLink)
                <li><a href="{{ $laptopApplyLink }}" target="_blank" rel="noopener noreferrer" class="mobile-nav-link">
                    <i class="fas fa-laptop fa-fw text-secondary"></i> PC/Laptop Apply <i class="fas fa-external-link-alt ms-1 small text-muted"></i></a></li>
                @endif

                {{-- Account --}}
                <li class="mobile-nav-section">Account</li>
                <li><a href="{{ route('user.personal-info') }}" class="mobile-nav-link {{ request()->routeIs('user.personal-info') ? 'active' : '' }}">
                    <i class="fas fa-user fa-fw text-info"></i> My Profile</a></li>
                @if($supportLink)
                <li><a href="{{ $supportLink }}" target="_blank" rel="noopener noreferrer" class="mobile-nav-link">
                    <i class="fas fa-headset fa-fw text-success"></i> Support <i class="fas fa-external-link-alt ms-1 small text-muted"></i></a></li>
                @endif
                <li><a href="{{ route('logout') }}" class="mobile-nav-link text-danger">
                    <i class="fas fa-sign-out-alt fa-fw"></i> Logout</a></li>
            </ul>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
