@extends('layouts.resident')
@section('title', 'Payment Confirmed!')
@section('content')

    <style>
        .confirm-page {
            background: #f0f0f0;
            min-height: 100vh;
            padding: 36px 40px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .confirm-wrapper {
            width: 100%;
            max-width: 680px;
        }

        .confirm-hero {
            background: #eef5ee;
            border-radius: 20px 20px 0 0;
            padding: 48px 40px 36px;
            text-align: center;
        }

        .confirm-hero .check-icon {
            font-size: 52px;
            color: #1B5E20;
            margin-bottom: 16px;
        }

        .confirm-hero h2 {
            font-size: 28px;
            font-weight: 800;
            color: #1B5E20;
            margin-bottom: 8px;
        }

        .confirm-hero p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        .confirm-details {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8e8e8;
            padding: 32px;
            margin-top: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .cd-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .cd-item .cd-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            margin-bottom: 4px;
        }

        .cd-item .cd-value {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .cd-item .cd-value i {
            font-size: 13px;
            color: #666;
        }

        .cd-divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 20px 0;
            grid-column: 1 / -1;
        }

        .cd-total-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cd-total-amount {
            font-size: 28px;
            font-weight: 800;
            color: #1B5E20;
        }

        .badge-confirmed-new {
            background: #1B5E20;
            color: #fff;
            border-radius: 20px;
            padding: 4px 16px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .premises-type-badge {
            display: inline-block;
            background: #E8F5E9;
            color: #2E7D32;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .agreement-badge {
            display: inline-block;
            background: #E3F2FD;
            color: #1565C0;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .confirm-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 24px;
        }

        .btn-view-applications {
            background: #fff;
            color: #1B5E20;
            border: 2px solid #1B5E20;
            border-radius: 12px;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s;
        }

        .btn-view-applications:hover {
            background: #f0f7f0;
            color: #1B5E20;
        }

        .btn-apply-another {
            background: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s;
        }

        .btn-apply-another:hover {
            background: #154d1a;
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .confirm-page {
                padding: 20px;
            }

            .confirm-hero {
                padding: 32px 24px;
            }

            .confirm-details {
                padding: 20px;
            }

            .cd-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .cd-divider {
                margin: 12px 0;
            }

            .confirm-actions {
                flex-direction: column;
                gap: 12px;
            }

            .btn-view-applications,
            .btn-apply-another {
                justify-content: center;
            }
        }
    </style>

    <div class="confirm-page">
        <div class="confirm-wrapper">

            <div class="confirm-hero">
                <div class="check-icon"><i class="bi bi-check-circle-fill"></i></div>
                <h2>Payment Confirmed!</h2>
                <p>Your payment was successful and your rental agreement has been generated.</p>
            </div>

            <div class="confirm-details">
                <div class="cd-grid">
                    <div class="cd-item">
                        <div class="cd-label">Payment ID:</div>
                        <div class="cd-value">#{{ $payment->payment_id }}</div>
                    </div>
                    <div class="cd-item">
                        <div class="cd-label">Status:</div>
                        <div class="cd-value"><span class="badge-confirmed-new">Completed</span></div>
                    </div>

                    {{-- Premises Information --}}
                    <div class="cd-item">
                        <div class="cd-label">Premises:</div>
                        <div class="cd-value">
                            <i class="bi bi-building"></i>
                            {{ $payment->application->premises->premises_name }}
                        </div>
                    </div>
                    <div class="cd-item">
                        <div class="cd-label">Type:</div>
                        <div class="cd-value">
                            <span class="premises-type-badge">{{ $payment->application->premises->type_label }}</span>
                        </div>
                    </div>

                    <div class="cd-item">
                        <div class="cd-label">Location:</div>
                        <div class="cd-value">
                            <i class="bi bi-geo-alt"></i>
                            {{ $payment->application->premises->location->location_name }}, Cameron Highlands
                        </div>
                    </div>
                    <div class="cd-item">
                        <div class="cd-label">Business Type:</div>
                        <div class="cd-value">{{ $payment->application->intended_business_type }}</div>
                    </div>

                    {{-- Payment Information --}}
                    <div class="cd-item">
                        <div class="cd-label">Payment Date:</div>
                        <div class="cd-value">
                            <i class="bi bi-calendar-check"></i>
                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y, g:i A') }}
                        </div>
                    </div>
                    <div class="cd-item">
                        <div class="cd-label">Card Number:</div>
                        <div class="cd-value">
                            <i class="bi bi-credit-card"></i>
                            {{ $payment->masked_card_number }}
                        </div>
                    </div>

                    @if ($payment->rentalAgreement)
                        <div class="cd-item">
                            <div class="cd-label">Agreement Status:</div>
                            <div class="cd-value">
                                <span class="agreement-badge">
                                    <i class="bi bi-file-earmark-check"></i>
                                    {{ ucfirst($payment->rentalAgreement->agreement_status) }}
                                </span>
                            </div>
                        </div>
                        <div class="cd-item">
                            <div class="cd-label">Agreement Period:</div>
                            <div class="cd-value">
                                <i class="bi bi-calendar-range"></i>
                                {{ $payment->rentalAgreement->agreement_start_date->format('d M Y') }} -
                                {{ $payment->rentalAgreement->agreement_end_date->format('d M Y') }}
                            </div>
                        </div>
                    @endif

                    <hr class="cd-divider">

                    <div class="cd-item" style="grid-column: 1 / -1;">
                        <div class="cd-total-label">Total Amount Paid:</div>
                        <div class="cd-total-amount">RM {{ number_format($payment->amount, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="confirm-actions">
                <a href="{{ route('resident.applications.show', $payment->application->application_id) }}"
                    class="btn-view-applications">
                    <i class="bi bi-eye"></i> View Application
                </a>
                <a href="{{ route('resident.premises.index') }}" class="btn-apply-another">
                    <i class="bi bi-plus-circle"></i> Browse More Premises
                </a>
            </div>

        </div>
    </div>
@endsection
