<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPRMS — Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(ellipse at top left, #4a8c3f 0%, #1B5E20 50%, #145214 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 40px 40px 36px;
            width: 100%;
            max-width: 680px;
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
            margin-bottom: 4px;
            letter-spacing: 1px;
        }

        .page-heading {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #333;
            margin-bottom: 6px;
            display: block;
            text-align: left;
        }

        .form-label .text-muted {
            font-weight: normal;
            color: #999;
        }

        .form-control {
            border: 1.5px solid #ddd;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: #333;
            transition: border-color 0.2s;
            width: 100%;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #1B5E20;
            box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #bbb;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
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

        .btn-create {
            background-color: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 13px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 16px;
        }

        .btn-create:hover {
            background-color: #154d1a;
        }

        .signin-link {
            font-size: 13px;
            color: #666;
            margin-top: 20px;
            margin-bottom: 0;
        }

        .signin-link a {
            color: #1B5E20;
            font-weight: 700;
            text-decoration: none;
        }

        .signin-link a:hover {
            text-decoration: underline;
        }

        .invalid-feedback {
            font-size: 12px;
            color: #dc3545;
            margin-top: 4px;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .text-center {
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .col-half {
            flex: 1;
            min-width: calc(50% - 10px);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-top: 8px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .radio-option input {
            margin: 0;
            cursor: pointer;
        }

        .radio-option label {
            margin: 0;
            font-size: 13px;
            cursor: pointer;
        }

        @media (max-width: 560px) {
            .col-half {
                min-width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="auth-card">
        <!-- Brand Header — ALL CENTERED -->
        <div class="text-center">
            <div class="brand-avatar">B.P.R.M.S.</div>
            <div class="brand-title">Business Premises Rental Management System</div>
            <p class="page-heading">Create a Resident Account</p>
        </div>

        @include('partials.flash')

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <!-- Row 1: First Name | Middle Name (side by side) -->
            <div class="row">
                <div class="col-half">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="resident_first_name"
                        class="form-control @error('resident_first_name') is-invalid @enderror"
                        value="{{ old('resident_first_name') }}" required>
                    @error('resident_first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-half">
                    <label class="form-label">Middle Name <span class="text-muted">(Optional)</span></label>
                    <input type="text" name="resident_middle_name" class="form-control"
                        value="{{ old('resident_middle_name') }}" placeholder="Optional">
                </div>
            </div>

            <!-- Row 2: Last Name (full width) -->
            <div class="form-group">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="resident_last_name"
                    class="form-control @error('resident_last_name') is-invalid @enderror"
                    value="{{ old('resident_last_name') }}" required>
                @error('resident_last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Row 3: IC Number (full width) -->
            <div class="form-group">
                <label class="form-label">IC / Identification Number <span class="text-danger">*</span></label>
                <input type="text" name="resident_ic_number"
                    class="form-control @error('resident_ic_number') is-invalid @enderror"
                    value="{{ old('resident_ic_number') }}" placeholder="e.g. 901231145678 (12 digits, no hyphens)"
                    maxlength="12" required>
                <div class="form-text" style="font-size: 11px; color: #999;">12 digits, no hyphens</div>
                @error('resident_ic_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Row 4: Email | Phone (side by side) -->
            <div class="row">
                <div class="col-half">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="resident_email"
                        class="form-control @error('resident_email') is-invalid @enderror"
                        value="{{ old('resident_email') }}" required>
                    @error('resident_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-half">
                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="tel" name="resident_phone"
                        class="form-control @error('resident_phone') is-invalid @enderror"
                        value="{{ old('resident_phone') }}" placeholder="e.g. 0123456789" required>
                    @error('resident_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Row 5: Residential Address (full width) -->
            <div class="form-group">
                <label class="form-label">Residential Address <span class="text-danger">*</span></label>
                <textarea name="resident_address" class="form-control @error('resident_address') is-invalid @enderror" rows="3"
                    required>{{ old('resident_address') }}</textarea>
                @error('resident_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Row 6: Residency Duration | Marital Status (side by side) -->
            <div class="row">
                <div class="col-half">
                    <label class="form-label">Years Residing in Cameron Highlands <span
                            class="text-danger">*</span></label>
                    <input type="number" name="residency_duration"
                        class="form-control @error('residency_duration') is-invalid @enderror"
                        value="{{ old('residency_duration', 0) }}" min="0" max="255" required>
                    @error('residency_duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-half">
                    <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                    <select name="marital_status" class="form-control @error('marital_status') is-invalid @enderror"
                        required>
                        <option value="">— Select —</option>
                        <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single
                        </option>
                        <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married
                        </option>
                        <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed
                        </option>
                        <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced
                        </option>
                    </select>
                    @error('marital_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Row 7: MDCH License Holder | Business Experience (side by side) -->
            <div class="row">
                <div class="col-half">
                    <label class="form-label">MDCH License Holder?</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="mdch_license_holder" value="1"
                                {{ old('mdch_license_holder') == '1' ? 'checked' : '' }}>
                            <span>Yes</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="mdch_license_holder" value="0"
                                {{ old('mdch_license_holder', '0') == '0' ? 'checked' : '' }}>
                            <span>No</span>
                        </label>
                    </div>
                </div>
                <div class="col-half">
                    <label class="form-label">Business Experience?</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="business_experience" value="1"
                                {{ old('business_experience') == '1' ? 'checked' : '' }}>
                            <span>Yes</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="business_experience" value="0"
                                {{ old('business_experience', '0') == '0' ? 'checked' : '' }}>
                            <span>No</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Row 8: Business Type (full width - conditional) -->
            <div class="form-group">
                <label class="form-label">Business Type <span class="text-muted">(if applicable)</span></label>
                <input type="text" name="business_type"
                    class="form-control @error('business_type') is-invalid @enderror"
                    value="{{ old('business_type') }}" placeholder="e.g. Food & Beverage, Retail, Handicraft">
                @error('business_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Row 9: Password | Confirm Password (side by side) -->
            <div class="row">
                <div class="col-half">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" name="resident_password" id="passwordInput" class="form-control"
                            required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('resident_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-half">
                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="resident_password_confirmation" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn-create">Create Account</button>
        </form>

        {{-- Centered sign-in link --}}
        <p class="signin-link text-center">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
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
    </script>

</body>

</html>
