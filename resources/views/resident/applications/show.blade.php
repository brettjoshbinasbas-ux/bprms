@extends('layouts.resident')
@section('title', 'Application #' . $application->application_id)
@section('content')

    <style>
        .show-page {
            background: #f0f0f0;
            min-height: 100vh;
            padding: 32px 40px;
        }

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #555;
            text-decoration: none;
            margin-bottom: 24px;
            font-weight: 500;
            transition: color 0.15s;
        }

        .back-link:hover {
            color: #1B5E20;
        }

        /* Page header */
        .show-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .show-header .app-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .show-header .app-created {
            font-size: 13px;
            color: #888;
        }

        /* Status badge */
        .badge-pill {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .badge-pill.pending {
            background: #FFF8E1;
            color: #F57F17;
            border: 1px solid #F9A825;
        }

        .badge-pill.approved {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #4CAF50;
        }

        .badge-pill.rejected {
            background: #FFEBEE;
            color: #C62828;
            border: 1px solid #EF5350;
        }

        .badge-pill.cancelled {
            background: #F5F5F5;
            color: #757575;
            border: 1px solid #BDBDBD;
        }

        /* Two-column grid layout */
        .two-column-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Detail cards */
        .detail-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            height: 100%;
        }

        .detail-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-card-title i {
            color: #1B5E20;
            font-size: 18px;
        }

        .detail-row {
            margin-bottom: 16px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .dr-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .dr-value {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .dr-value i {
            font-size: 14px;
            color: #666;
        }

        .rental-fee {
            font-size: 24px;
            font-weight: 800;
            color: #1B5E20;
            margin-top: 8px;
        }

        /* Admin remarks section */
        .remarks-box {
            background: #FFF8E1;
            border-left: 4px solid #F9A825;
            padding: 16px;
            border-radius: 12px;
            margin-top: 16px;
        }

        .remarks-label {
            font-size: 11px;
            font-weight: 700;
            color: #F57F17;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .remarks-text {
            font-size: 13px;
            color: #555;
            line-height: 1.5;
        }

        /* Payment card */
        .pay-row {
            margin-bottom: 16px;
        }

        .pay-row .pr-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pay-row .pr-value {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        /* Status Timeline */
        .timeline-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 24px 0 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .timeline-title i {
            color: #1B5E20;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
        }

        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #1B5E20;
            flex-shrink: 0;
            margin-top: 4px;
        }

        .timeline-dot.grey {
            background: #ccc;
        }

        .timeline-event {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .timeline-date {
            font-size: 12px;
            color: #888;
            margin-top: 2px;
        }

        /* Documents section */
        .documents-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 8px;
        }

        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background: #fafafa;
            border-radius: 12px;
            border: 1px solid #f0f0f0;
        }

        .doc-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .doc-info i {
            font-size: 20px;
            color: #1B5E20;
        }

        .doc-name {
            font-size: 13px;
            font-weight: 500;
            color: #333;
        }

        .doc-date {
            font-size: 10px;
            color: #aaa;
            margin-top: 2px;
        }

        .btn-view-doc {
            background: none;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 12px;
            color: #1B5E20;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-view-doc:hover {
            background: #E8F5E9;
            border-color: #1B5E20;
        }

        /* Payment button */
        .payment-section {
            margin-top: 32px;
            text-align: right;
        }

        .btn-payment {
            background: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px 36px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-payment:hover {
            background: #154d1a;
        }

        .btn-cancel {
            background: #C62828;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 32px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cancel:hover {
            background: #b71c1c;
        }

        /* Divider */
        .detail-divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 20px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .two-column-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .show-page {
                padding: 20px;
            }

            .doc-item {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .btn-view-doc {
                align-self: flex-start;
            }
        }
    </style>

    <div class="show-page">

        {{-- Back --}}
        <a href="{{ route('resident.applications.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> My Applications
        </a>

        {{-- Header --}}
        <div class="show-header">
            <div>
                <div class="app-title">Application #{{ $application->application_id }}</div>
                <div class="app-created">
                    Submitted on {{ \Carbon\Carbon::parse($application->application_date)->format('d M Y, g:i A') }}
                </div>
            </div>
            @php
                $statusClass = match ($application->application_status) {
                    'pending' => 'pending',
                    'approved' => 'approved',
                    'rejected' => 'rejected',
                    'cancelled' => 'cancelled',
                    default => 'pending',
                };
            @endphp
            <span class="badge-pill {{ $statusClass }}">{{ ucfirst($application->application_status) }}</span>
        </div>

        {{-- Two-column grid layout --}}
        <div class="two-column-grid">

            {{-- LEFT: Application Details --}}
            <div class="detail-card">
                <div class="detail-card-title">
                    <i class="bi bi-file-earmark-text"></i> Application Details
                </div>

                <div class="detail-row">
                    <div class="dr-label">Premises Name</div>
                    <div class="dr-value">
                        <i class="bi bi-building"></i>
                        {{ $application->premises->premises_name ?? '—' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="dr-label">Location</div>
                    <div class="dr-value">
                        <i class="bi bi-geo-alt"></i>
                        {{ $application->premises->location->location_name ?? '—' }}, Cameron Highlands
                    </div>
                </div>

                <div class="detail-row">
                    <div class="dr-label">Premises Type</div>
                    <div class="dr-value">
                        <i class="bi bi-tag"></i>
                        {{ $application->premises->type_label ?? '—' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="dr-label">Intended Business Type</div>
                    <div class="dr-value">
                        <i class="bi bi-briefcase"></i>
                        {{ $application->intended_business_type }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="dr-label">Financial Position</div>
                    <div class="dr-value">
                        <i class="bi bi-cash-stack"></i>
                        RM {{ number_format($application->financial_position, 2) }}
                    </div>
                </div>

                @if ($application->remarks)
                    <div class="remarks-box">
                        <div class="remarks-label">
                            <i class="bi bi-chat-dots"></i> Admin Remarks
                        </div>
                        <div class="remarks-text">{{ $application->remarks }}</div>
                    </div>
                @endif

                <hr class="detail-divider">

                <div class="dr-label">Monthly Rental Fee</div>
                <div class="rental-fee">
                    RM {{ number_format($application->premises->rental_fee ?? 0, 2) }}
                    <span style="font-size: 14px; font-weight: normal;">/month</span>
                </div>
            </div>

            {{-- RIGHT: Payment, Documents & Timeline --}}
            <div class="detail-card">
                <div class="detail-card-title">
                    <i class="bi bi-credit-card"></i> Payment & Documents
                </div>

                {{-- Payment Section --}}
                @if ($application->payment)
                    <div class="pay-row">
                        <div class="pr-label">Payment Date</div>
                        <div class="pr-value">
                            {{ \Carbon\Carbon::parse($application->payment->payment_date)->format('d M Y') }}
                        </div>
                    </div>
                    <div class="pay-row">
                        <div class="pr-label">Amount Paid</div>
                        <div class="pr-value">RM {{ number_format($application->payment->amount, 2) }}</div>
                    </div>
                    <div class="pay-row">
                        <div class="pr-label">Card Number</div>
                        <div class="pr-value">{{ $application->payment->masked_card_number }}</div>
                    </div>
                @else
                    <div class="pay-row">
                        <div class="pr-label">Payment Status</div>
                        <div class="pr-value" style="color:#888;">No payment recorded</div>
                    </div>
                @endif

                <hr class="detail-divider">

                {{-- Documents Section --}}
                <div class="detail-row">
                    <div class="dr-label">
                        <i class="bi bi-paperclip"></i> Uploaded Documents
                    </div>
                    @if ($application->documents->isEmpty())
                        <div class="dr-value" style="color:#888;">No documents uploaded</div>
                    @else
                        <div class="documents-grid">
                            @foreach ($application->documents as $doc)
                                <div class="doc-item">
                                    <div class="doc-info">
                                        @if (str_contains($doc->document_type, 'photo'))
                                            <i class="bi bi-camera"></i>
                                        @elseif (str_contains($doc->document_type, 'ic'))
                                            <i class="bi bi-card-text"></i>
                                        @else
                                            <i class="bi bi-file-earmark"></i>
                                        @endif
                                        <div>
                                            <div class="doc-name">{{ $doc->type_label }}</div>
                                            <div class="doc-date">{{ $doc->uploaded_at->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($doc->document_path) }}" target="_blank" class="btn-view-doc">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <hr class="detail-divider">

                {{-- Status Timeline --}}
                <div class="timeline-title">
                    <i class="bi bi-clock-history"></i> Status Timeline
                </div>

                {{-- Application Submitted --}}
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div>
                        <div class="timeline-event">Application Submitted</div>
                        <div class="timeline-date">
                            {{ \Carbon\Carbon::parse($application->application_date)->format('d M Y, g:i A') }}
                        </div>
                    </div>
                </div>

                {{-- Admin Review (if approved or rejected) --}}
                @if (in_array($application->application_status, ['approved', 'rejected']))
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div>
                            <div class="timeline-event">
                                Application {{ ucfirst($application->application_status) }} by Admin
                            </div>
                            <div class="timeline-date">
                                {{ \Carbon\Carbon::parse($application->reviewed_at)->format('d M Y, g:i A') }}
                                @if ($application->reviewer)
                                    • Reviewed by {{ $application->reviewer->full_name }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Payment (if completed) --}}
                @if ($application->payment)
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div>
                            <div class="timeline-event">Payment Completed</div>
                            <div class="timeline-date">
                                {{ \Carbon\Carbon::parse($application->payment->payment_date)->format('d M Y, g:i A') }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Rental Agreement (if active) --}}
                @if ($application->rentalAgreement)
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div>
                            <div class="timeline-event">Rental Agreement Active</div>
                            <div class="timeline-date">
                                {{ $application->rentalAgreement->agreement_start_date->format('d M Y') }} -
                                {{ $application->rentalAgreement->agreement_end_date->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Cancellation --}}
                @if ($application->application_status === 'cancelled')
                    <div class="timeline-item">
                        <div class="timeline-dot grey"></div>
                        <div>
                            <div class="timeline-event">Application Cancelled</div>
                            <div class="timeline-date">
                                {{ \Carbon\Carbon::parse($application->updated_at)->format('d M Y, g:i A') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        @if ($application->application_status === 'approved' && !$application->payment)
            <div class="payment-section">
                <a href="{{ route('resident.payment.form', $application->application_id) }}" class="btn-payment">
                    <i class="bi bi-credit-card"></i> Proceed to Payment
                </a>
            </div>
        @endif

        @if ($application->application_status === 'pending')
            <div class="payment-section">
                <form method="POST" action="{{ route('resident.applications.cancel', $application->application_id) }}"
                    onsubmit="return confirm('Are you sure you want to cancel this application? This action cannot be undone.')">
                    @csrf
                    <button type="submit" class="btn-cancel">
                        <i class="bi bi-x-circle"></i> Cancel Application
                    </button>
                </form>
            </div>
        @endif

    </div>

@endsection
