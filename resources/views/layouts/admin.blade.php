<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPRMS — @yield('title', 'Admin')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f0f0f0;
            font-family: 'Inter', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .admin-sidebar {
            width: 260px;
            height: 100vh;
            background: #fff;
            border-right: 1px solid #e8e8e8;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            overflow: hidden;
        }

        /* Scrollable navigation area */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 0;
        }

        /* Custom scrollbar styling */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: #f0f0f0;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #F9A825;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-text .brand-name {
            font-size: 15px;
            font-weight: 800;
            color: #1a1a1a;
            line-height: 1;
        }

        .sidebar-brand .brand-text .brand-sub {
            font-size: 11px;
            color: #aaa;
            margin-top: 2px;
        }

        .nav-section {
            font-size: 10px;
            font-weight: 700;
            color: #bbb;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 20px 4px;
        }

        .nav-section:first-child {
            padding-top: 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            text-decoration: none;
            border-radius: 0;
            transition: all 0.15s;
            border-left: 3px solid transparent;
        }

        .sidebar-link i {
            font-size: 15px;
            color: #888;
            flex-shrink: 0;
        }

        .sidebar-link:hover {
            background: #f5f5f5;
            color: #1B5E20;
        }

        .sidebar-link:hover i {
            color: #1B5E20;
        }

        .sidebar-link.active {
            background: #E8F5E9;
            color: #1B5E20;
            font-weight: 700;
            border-left-color: #1B5E20;
        }

        .sidebar-link.active i {
            color: #1B5E20;
        }

        .sidebar-link .badge-count {
            margin-left: auto;
            background: #F9A825;
            color: #fff;
            border-radius: 10px;
            padding: 1px 7px;
            font-size: 11px;
            font-weight: 700;
        }

        /* Sidebar Footer - always visible at bottom */
        .sidebar-footer {
            padding: 16px 16px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            flex-shrink: 0;
        }

        .sidebar-footer .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #1B5E20;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-footer .user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-footer .user-info .u-name {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a1a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-footer .user-info .u-role {
            font-size: 10px;
            color: #aaa;
        }

        .sidebar-footer .btn-logout-sm {
            background: none;
            border: none;
            cursor: pointer;
            color: #1B5E20;
            font-size: 18px;
            padding: 6px;
            transition: all 0.15s;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }

        .sidebar-footer .btn-logout-sm:hover {
            background: #FFEBEE;
            color: #C62828;
        }

        /* ── Main ── */
        .admin-main {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
            padding: 36px 40px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 70px;
            }

            .sidebar-brand .brand-text,
            .sidebar-link span:not(.badge-count),
            .nav-section,
            .user-info {
                display: none;
            }

            .sidebar-link {
                justify-content: center;
                padding: 12px;
            }

            .sidebar-link i {
                margin: 0;
                font-size: 20px;
            }

            .admin-main {
                margin-left: 70px;
                padding: 20px;
            }

            .sidebar-footer {
                justify-content: center;
            }

            .sidebar-footer .user-avatar {
                margin: 0 auto;
            }

            .btn-logout-sm {
                position: static;
                transform: none;
            }
        }
    </style>
</head>

<body>

    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="brand-avatar">BP</div>
            <div class="brand-text">
                <div class="brand-name">BPRMS</div>
                <div class="brand-sub">Admin Panel — MDCH</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Overview</div>
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>

            <div class="nav-section">Manage</div>
            <a href="{{ route('admin.locations.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i> <span>Locations</span>
            </a>
            <a href="{{ route('admin.premises.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.premises.*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i> <span>Premises</span>
            </a>

            <div class="nav-section">Applications</div>
            <a href="{{ route('admin.applications.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> <span>All Applications</span>
                @if (isset($pendingCount) && $pendingCount > 0)
                    <span class="badge-count">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.agreements.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.agreements.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-check"></i> <span>Rental Agreements</span>
            </a>

            <div class="nav-section">Reports</div>
            <a href="{{ route('admin.reports.active-agreements') }}"
                class="sidebar-link {{ request()->routeIs('admin.reports.active-agreements') ? 'active' : '' }}">
                <i class="bi bi-journal-check"></i> <span>Active Agreements</span>
            </a>
            <a href="{{ route('admin.reports.revenue') }}"
                class="sidebar-link {{ request()->routeIs('admin.reports.revenue') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> <span>Revenue Summary</span>
            </a>
            <a href="{{ route('admin.reports.applications') }}"
                class="sidebar-link {{ request()->routeIs('admin.reports.applications') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> <span>Application Stats</span>
            </a>
            <a href="{{ route('admin.reports.occupancy') }}"
                class="sidebar-link {{ request()->routeIs('admin.reports.occupancy') ? 'active' : '' }}">
                <i class="bi bi-buildings"></i> <span>Occupancy</span>
            </a>

            <div class="nav-section">Users</div>
            <a href="{{ route('admin.residents.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.residents.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span>Residents</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-avatar">{{ strtoupper(substr(auth('admin')->user()->admin_first_name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="u-name">{{ auth('admin')->user()->full_name }}</div>
                <div class="u-role">Administrator</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout-sm" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
