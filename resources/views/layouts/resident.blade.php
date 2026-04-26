<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPRMS — @yield('title', 'Resident Portal')</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap CSS (optional but helpful) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f0f0f0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* ── Top Navbar ── */
        .user-nav {
            background: #1B5E20;
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 200;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .user-nav .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-right: 40px;
        }

        .user-nav .brand-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #F9A825;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }

        .user-nav .brand-name {
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .user-nav .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        .user-nav .nav-links a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.15s;
        }

        .user-nav .nav-links a:hover,
        .user-nav .nav-links a.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
        }

        .user-nav .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-left: auto;
        }

        .user-nav .nav-user {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 12px;
            border-radius: 24px;
        }

        .user-nav .nav-user i {
            font-size: 16px;
            opacity: 0.8;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 8px;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        /* ── Page wrapper ── */
        .user-page-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px 32px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .user-nav {
                padding: 0 16px;
            }

            .user-nav .brand-name {
                font-size: 16px;
            }

            .user-nav .nav-links a {
                padding: 6px 10px;
                font-size: 13px;
            }

            .user-nav .nav-user span {
                display: none;
            }

            .btn-logout span {
                display: none;
            }

            .btn-logout i {
                margin: 0;
            }

            .user-page-wrap {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

    {{-- Top Navigation --}}
    <nav class="user-nav">
        <a href="{{ route('resident.dashboard') }}" class="nav-brand">
            <div class="brand-avatar">BP</div>
            <span class="brand-name">BPRMS</span>
        </a>

        <div class="nav-links">
            <a href="{{ route('resident.dashboard') }}" class="{{ request()->routeIs('resident.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('resident.premises.index') }}"
                class="{{ request()->routeIs('resident.premises.*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i> Browse Premises
            </a>
            <a href="{{ route('resident.applications.index') }}"
                class="{{ request()->routeIs('resident.applications.*') ? 'active' : '' }}">
                <i class="bi bi-file-text"></i> My Applications
            </a>
        </div>

        <div class="nav-right">
            <div class="nav-user">
                <i class="bi bi-person-circle"></i>
                <span>{{ auth('resident')->user()->full_name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    {{-- Content --}}
    <div class="user-page-wrap">
        @include('partials.flash')
        @yield('content')
    </div>

    <!-- Bootstrap JS (for dropdowns, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>