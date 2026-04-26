<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPRMS — Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(ellipse at top left, #4a8c3f 0%, #1B5E20 50%, #145214 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 40px 40px 36px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.18);
        }

        .brand-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #F9A825;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin: 0 auto 12px;
            letter-spacing: 1px;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 800;
            color: #1B5E20;
            margin-bottom: 2px;
            letter-spacing: 1px;
        }

        .brand-sub {
            font-size: 13px;
            color: #616161;
            margin-bottom: 0;
        }

        .sign-in-heading {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-top: 24px;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #444;
            margin-bottom: 8px;
            display: block;
            text-align: left;
        }

        .form-control {
            border: 1.5px solid #ddd;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: #333;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #1B5E20;
            box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #bbb;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 44px;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #999;
            font-size: 16px;
            line-height: 1;
        }

        .password-toggle:hover {
            color: #555;
        }

        .btn-signin {
            background-color: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 16px;
        }

        .btn-signin:hover {
            background-color: #154d1a;
        }

        .register-link {
            font-size: 13px;
            color: #666;
            margin-top: 24px;
            margin-bottom: 0;
        }

        .register-link a {
            color: #1B5E20;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .text-center {
            text-align: center;
        }

        /* Role selector styles */
        .role-selector {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .role-option {
            flex: 1;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.15s;
            background: #fff;
        }

        .role-option.selected {
            border-color: #1B5E20;
            background: #E8F5E9;
        }

        .role-option i {
            font-size: 24px;
            display: block;
            margin-bottom: 8px;
            color: #666;
        }

        .role-option.selected i {
            color: #1B5E20;
        }

        .role-option .role-label {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
        }

        .role-option.selected .role-label {
            color: #1B5E20;
        }

        .form-group {
            margin-bottom: 24px;
        }
    </style>
</head>

<body>

    <div class="auth-card">
        {{-- Centered header section --}}
        <div class="text-center">
            <div class="brand-avatar">B.P.R.M.S.</div>
            <div class="brand-title">Business Premises Rental Management System</div>
            <p class="brand-sub">Cameron Highland District Council</p>
        </div>

        {{-- Centered heading --}}
        <h5 class="sign-in-heading text-center">Sign In to Your Account</h5>

        @include('partials.flash')

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            {{-- Role selector --}}
            <div class="form-group">
                <label class="form-label">Sign in as</label>
                <div class="role-selector">
                    <div class="role-option {{ old('role', 'resident') === 'resident' ? 'selected' : '' }}"
                        data-role="resident">
                        <i class="bi bi-person"></i>
                        <p class="role-label">Resident</p>
                    </div>
                    <div class="role-option {{ old('role') === 'admin' ? 'selected' : '' }}" data-role="admin">
                        <i class="bi bi-shield-lock"></i>
                        <p class="role-label">Administrator</p>
                    </div>
                </div>
                <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'resident') }}">
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email field --}}
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password field --}}
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="passwordInput" class="form-control"
                        placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-signin">Sign In</button>
        </form>

        {{-- Centered register link --}}
        <p class="register-link text-center">
            Don't have an account? <a href="{{ route('register') }}">Register here</a>
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        // Role selector functionality
        document.querySelectorAll('.role-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.role-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
                document.getElementById('roleInput').value = this.dataset.role;
            });
        });
    </script>

</body>

</html>
