@extends('layouts.resident')
@section('title', 'Payment')
@section('content')

    <style>
        .payment-page {
            background: #f0f0f0;
            min-height: 100vh;
            padding: 36px 40px;
        }

        .payment-page-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 28px;
        }

        /* Two column grid */
        .two-column-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .summary-card,
        .payment-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            height: 100%;
        }

        .card-title-bar {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-title-bar i {
            font-size: 18px;
            color: #1B5E20;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f5f5f5;
        }

        .summary-row .sr-label {
            font-size: 13px;
            color: #888;
        }

        .summary-row .sr-value {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            text-align: right;
        }

        .summary-divider {
            border: none;
            border-top: 2px solid #f0f0f0;
            margin: 16px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }

        .total-row .total-label {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .total-row .total-amount {
            font-size: 24px;
            font-weight: 800;
            color: #1B5E20;
        }

        .notice-box {
            background: #FFF8E1;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 12px;
            color: #8a6500;
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-left: 4px solid #F9A825;
        }

        .notice-box-blue {
            background: #E3F2FD;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 12px;
            color: #1565C0;
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-left: 4px solid #1565C0;
        }

        .form-label-new {
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
            display: block;
        }

        .form-label-new i {
            margin-right: 4px;
            color: #888;
        }

        .form-input-new {
            width: 100%;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            color: #333;
            background: #fff;
            margin-bottom: 20px;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input-new:focus {
            border-color: #1B5E20;
            outline: none;
            box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.1);
        }

        .form-input-new.is-invalid {
            border-color: #dc3545;
        }

        select.form-input-new {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
        }

        .btn-pay {
            width: 100%;
            background: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-pay:hover {
            background: #154d1a;
        }

        .btn-back {
            background: #fff;
            color: #444;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
        }

        .btn-back:hover {
            background: #f5f5f5;
            color: #222;
            border-color: #ccc;
        }

        /* Premises Type Badge */
        .premises-type-badge {
            display: inline-block;
            background: #E8F5E9;
            color: #2E7D32;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-page {
                padding: 20px;
            }

            .two-column-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }
    </style>

    <div class="payment-page">

        <div class="payment-page-title">Complete Your Payment</div>

        <div class="two-column-grid">
            {{-- LEFT: Payment Summary --}}
            <div class="summary-card">
                <div class="card-title-bar">
                    <i class="bi bi-receipt"></i> Payment Summary
                </div>

                <div class="summary-row">
                    <span class="sr-label">Premises:</span>
                    <span class="sr-value">{{ $application->premises->premises_name }}</span>
                </div>
                <div class="summary-row">
                    <span class="sr-label">Type:</span>
                    <span class="sr-value">
                        <span class="premises-type-badge">{{ $application->premises->type_label }}</span>
                    </span>
                </div>
                <div class="summary-row">
                    <span class="sr-label">Location:</span>
                    <span class="sr-value">{{ $application->premises->location->location_name }}, Cameron Highlands</span>
                </div>
                <div class="summary-row">
                    <span class="sr-label">Business Type:</span>
                    <span class="sr-value">{{ $application->intended_business_type }}</span>
                </div>

                <hr class="summary-divider">

                <div class="summary-row">
                    <span class="sr-label">Application ID:</span>
                    <span class="sr-value">#{{ $application->application_id }}</span>
                </div>
                <div class="summary-row">
                    <span class="sr-label">Approved Date:</span>
                    <span class="sr-value">{{ \Carbon\Carbon::parse($application->reviewed_at)->format('d M Y') }}</span>
                </div>

                <hr class="summary-divider">

                <div class="total-row">
                    <span class="total-label">Total Amount (1 year):</span>
                    <span class="total-amount">RM {{ number_format($application->premises->rental_fee, 2) }}</span>
                </div>

                <div class="notice-box">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Payment covers a 1-year rental agreement. Agreement starts immediately upon payment.</span>
                </div>
            </div>

            {{-- RIGHT: Payment Form --}}
            <div class="payment-card">
                <div class="card-title-bar">
                    <i class="bi bi-credit-card"></i> Card Payment Details
                </div>

                @include('partials.flash')

                <form method="POST" action="{{ route('resident.payment.process') }}">
                    @csrf
                    <input type="hidden" name="application_id" value="{{ $application->application_id }}">

                    <label class="form-label-new">
                        <i class="bi bi-person"></i> Cardholder Name
                    </label>
                    <input type="text" name="cardholder_name"
                        class="form-input-new @error('cardholder_name') is-invalid @enderror" placeholder="John Doe"
                        required>
                    @error('cardholder_name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    <label class="form-label-new">
                        <i class="bi bi-credit-card"></i> Card Number
                    </label>
                    <input type="text" name="card_number"
                        class="form-input-new @error('card_number') is-invalid @enderror" placeholder="1234 5678 9012 3456"
                        maxlength="19" oninput="this.value=this.value.replace(/\D/g,'').replace(/(.{4})/g,'$1 ').trim()"
                        required>
                    @error('card_number')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    {{-- Expiry Date: Month and Year Dropdowns --}}
                    <label class="form-label-new">
                        <i class="bi bi-calendar"></i> Expiry Date
                    </label>
                    <div class="row g-2">
                        <div class="col-6">
                            <select name="expiry_month" class="form-input-new" required>
                                <option value="">Month</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ sprintf('%02d', $m) }}"
                                        {{ old('expiry_month') == sprintf('%02d', $m) ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $m) }} - {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <select name="expiry_year" class="form-input-new" required>
                                <option value="">Year</option>
                                @for ($y = date('Y'); $y <= date('Y') + 10; $y++)
                                    <option value="{{ $y }}" {{ old('expiry_year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    @error('expiry_month')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('expiry_year')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    <div class="notice-box-blue">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Card details are for simulation purposes only. No real transaction is processed.</span>
                    </div>

                    <button type="submit" class="btn-pay">
                        <i class="bi bi-lock-fill"></i> Pay RM {{ number_format($application->premises->rental_fee, 2) }} &
                        Confirm
                    </button>
                </form>
            </div>
        </div>

        <div>
            <a href="{{ route('resident.applications.show', $application->application_id) }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Back to Application
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            // Optional: Additional card number formatting
            document.querySelectorAll('input[name="card_number"]').forEach(input => {
                input.addEventListener('input', function(e) {
                    let value = this.value.replace(/\s/g, '');
                    if (value.length > 0) {
                        this.value = value.match(/.{1,4}/g).join(' ');
                    }
                });
            });
        </script>
    @endpush
@endsection
