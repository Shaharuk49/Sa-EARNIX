<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $pendingReg = \App\Models\RegistrationPayment::where('status', 'pending')->count();
        $adminName = Auth::guard('admin')->user()->name ?? 'Administrator';
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin – SA EarniX')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --admin-primary: #1e3a8a; --admin-dark: #0f172a; --sidebar-w: 260px; }
        html, body {
            min-height: 100%;
        }
        body {
            background: transparent;
            overflow-y: auto !important;
            overflow-x: hidden;
        }
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--admin-dark);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform .3s ease, visibility .3s ease;
            overflow-y: auto;
            overscroll-behavior: contain;
        }
        .topbar {
            margin-left: var(--sidebar-w);
            background: #fff;
            padding: 12px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }
        .mobile-sidebar-toggle {
            touch-action: manipulation;
            cursor: pointer !important;
            -webkit-tap-highlight-color: transparent;
        }
        .main-content {
            margin-left: var(--sidebar-w);
            padding: 24px;
            min-height: calc(100vh - 60px);
            overflow-x: hidden;
            padding-bottom: 42px;
        }
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 20px;
        }
        .badge-pill {
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .75rem;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                visibility: hidden;
                pointer-events: none;
            }
            .sidebar.open {
                transform: translateX(0);
                visibility: visible;
                pointer-events: auto;
            }
            .topbar, .main-content { margin-left: 0; }
        }

        .mobile-sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            z-index: 1030;
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s ease, visibility .2s ease;
        }

        .mobile-sidebar-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        body.mobile-sidebar-open {
            overflow: hidden;
        }
    </style>
</head>
<body class="page-shell"
      data-flash-success="{{ session('success') }}"
      data-flash-error="{{ session('error') }}">

<div class="mobile-sidebar-backdrop" id="mobile-sidebar-backdrop" aria-hidden="true"></div>

<div class="sidebar" id="sidebar" role="navigation" aria-label="Admin sidebar">
    <div class="admin-sidebar-shell">
        <div class="sidebar-brand admin-sidebar-brand">
            <div class="d-flex align-items-center gap-3">
                <span class="admin-brand-badge">
                    <i class="fas fa-crown"></i>
                </span>
                <div>
                    <h5 class="admin-brand-title">SA EarniX Admin</h5>
                    <small class="admin-brand-text">Control center for users, payments, and content</small>
                </div>
            </div>
            <div class="admin-sidebar-meta">
                <div>
                    <span class="admin-meta-label">Signed In</span>
                    <span class="admin-meta-value">{{ $adminName }}</span>
                </div>
                <span class="badge text-bg-light">Live Panel</span>
            </div>
        </div>

        <nav class="sidebar-nav admin-sidebar-nav">
            <div class="admin-nav-group">
                <div class="nav-section">Overview</div>
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-chart-pie"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">Dashboard</span>
                        <span class="admin-nav-hint">Platform summary and alerts</span>
                    </span>
                </a>
            </div>

            <div class="admin-nav-group">
                <div class="nav-section">Users</div>
                <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-users"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">All Users</span>
                        <span class="admin-nav-hint">Profiles, status, and network view</span>
                    </span>
                </a>
                <a href="{{ route('admin.registrations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.registrations*') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-money-check-dollar"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">Registration Payments</span>
                        <span class="admin-nav-hint">Approve new member payments</span>
                    </span>
                    @if($pendingReg > 0)
                        <span class="admin-nav-pill">{{ $pendingReg }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.withdraw.index') }}" class="admin-nav-link {{ request()->routeIs('admin.withdraw*') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-wallet"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">Withdraw Requests</span>
                        <span class="admin-nav-hint">Approve or reject payout requests</span>
                    </span>
                </a>
            </div>

            <div class="admin-nav-group">
                <div class="nav-section">Income Settings</div>
                <a href="{{ route('admin.commissions.index') }}" class="admin-nav-link {{ request()->routeIs('admin.commissions*') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-percent"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">Commission Setup</span>
                        <span class="admin-nav-hint">Manage 24-generation payout rules</span>
                    </span>
                </a>
                <a href="{{ route('admin.salary.index') }}" class="admin-nav-link {{ request()->routeIs('admin.salary*') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-money-check-alt"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">Monthly Salary</span>
                        <span class="admin-nav-hint">Levels, rules, and activation control</span>
                    </span>
                </a>
                <a href="{{ route('admin.badges.index') }}" class="admin-nav-link {{ request()->routeIs('admin.badges*') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-medal"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">Leader Badges</span>
                        <span class="admin-nav-hint">Badge names, prizes, and conditions</span>
                    </span>
                </a>
                <a href="{{ route('admin.withdraw-methods.index') }}" class="admin-nav-link {{ request()->routeIs('admin.withdraw-methods*') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-credit-card"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">Withdraw Methods</span>
                        <span class="admin-nav-hint">Payment channels and availability</span>
                    </span>
                </a>
            </div>

            <div class="admin-nav-group">
                <div class="nav-section">Content</div>
                <a href="{{ route('admin.bonus.index') }}" class="admin-nav-link {{ request()->routeIs('admin.bonus*') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-gift"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">Bonus Sections</span>
                        <span class="admin-nav-hint">Videos and unlock criteria</span>
                    </span>
                </a>
                <a href="{{ route('admin.official-links.index') }}" class="admin-nav-link {{ request()->routeIs('admin.official-links*') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-link"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">Official Links</span>
                        <span class="admin-nav-hint">Managed social and support links</span>
                    </span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <span class="admin-nav-icon"><i class="fas fa-sliders"></i></span>
                    <span class="admin-nav-copy">
                        <span class="admin-nav-title">Site Settings</span>
                        <span class="admin-nav-hint">General configuration and buttons</span>
                    </span>
                </a>
            </div>

            <div class="admin-sidebar-footer">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="admin-logout-button">
                        <span class="admin-nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="admin-nav-copy">
                            <span class="admin-nav-title">Logout</span>
                            <span class="admin-nav-hint">End this admin session securely</span>
                        </span>
                    </button>
                </form>
                <div class="admin-footer-hint">Secure administration workspace</div>
            </div>
        </nav>
    </div>
</div>

<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-outline-secondary d-md-none mobile-sidebar-toggle"
                type="button"
                data-mobile-sidebar-toggle
                data-mobile-sidebar-target="#sidebar"
                aria-controls="sidebar"
                aria-expanded="false"
                aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <h6 class="mb-0 fw-semibold text-dark">@yield('page-title', 'Dashboard')</h6>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary">Admin</span>
        <span class="text-muted small">{{ $adminName }}</span>
    </div>
</div>

<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

@stack('scripts')
</body>
</html>
