@extends('layouts.admin')
@section('title', 'Agreement #' . $agreement->agreement_id)
@section('content')

    <style>
        .show-page {
            background: #f0f0f0;
            min-height: 100vh;
            padding: 32px 40px;
        }

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

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .page-sub {
            font-size: 13px;
            color: #888;
        }

        /* Status Badge */
        .badge-pill {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .badge-pill.active {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #4CAF50;
        }

        .badge-pill.expired {
            background: #F5F5F5;
            color: #757575;
            border: 1px solid #BDBDBD;
        }

        .badge-pill.terminated {
            background: #FFEBEE;
            color: #C62828;
            border: 1px solid #EF5350;
        }

        /* Cards */
        .detail-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .card-header-custom {
            padding: 16px 24px;
            border-bottom: 1px solid #f0f0f0;
            background: #fff;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-header-custom i {
            color: #1B5E20;
            font-size: 18px;
        }

        .card-body-custom {
            padding: 24px;
        }

        /* Premises Badge */
        .premises-badge {
            background: #E3F2FD;
            color: #1565C0;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        /* Vacancy Prompt Banner */
        .vacancy-banner {
            background: #E8F5E9;
            border: 1px solid #4CAF50;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .vacancy-banner i {
            color: #2E7D32;
            font-size: 20px;
        }

        .btn-publish {
            background: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
            white-space: nowrap;
        }

        .btn-publish:hover {
            background: #154d1a;
        }

        /* Terminate Button */
        .terminate-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
        }

        .terminate-header {
            padding: 16px 24px;
            border-bottom: 1px solid #f0f0f0;
            background: #FFEBEE;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #C62828;
        }

        .terminate-header i {
            color: #C62828;
        }

        .terminate-body {
            padding: 24px;
        }

        .btn-terminate {
            background: #C62828;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: background 0.15s;
        }

        .btn-terminate:hover {
            background: #b71c1c;
        }

        @media (max-width: 768px) {
            .show-page {
                padding: 20px;
            }
        }
    </style>

    <div class="show-page">

        {{-- Back Link --}}
        <a href="{{ route('admin.agreements.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> All Agreements
        </a>

        {{-- Header --}}
        <div class="page-header">
            <div>
                <div class="page-title">Agreement #{{ $agreement->agreement_id }}</div>
                <div class="page-sub">
                    Signed on {{ $agreement->signed_at->format('F j, Y \a\t g:i A') }}
                </div>
            </div>
            @php
                $statusClass = match ($agreement->agreement_status) {
                    'active' => 'active',
                    'expired' => 'expired',
                    'terminated' => 'terminated',
                    default => 'active',
                };
            @endphp
            <span class="badge-pill {{ $statusClass }}">{{ ucfirst($agreement->agreement_status) }}</span>
        </div>

        @include('partials.flash')

        {{-- Vacancy Prompt After Termination --}}
        @if (session('success') && str_contains(session('success'), 'vacancy_prompt='))
            @php
                preg_match('/vacancy_prompt=(\d+)/', session('success'), $m);
                $vpId = $m[1] ?? null;
                $vpPremises = $vpId ? \App\Models\Premises::with('location')->find($vpId) : null;
            @endphp
            @if ($vpPremises)
                <div class="vacancy-banner">
                    <div>
                        <i class="bi bi-megaphone-fill me-2"></i>
                        <strong>{{ $vpPremises->premises_name }}</strong> has been released and is now available.
                        Publish a vacancy notice to notify all residents?
                    </div>
                    <form method="POST"
                        action="{{ url('/admin/premises/' . $vpPremises->premises_id . '/publish-vacancy') }}">
                        @csrf
                        <button type="submit" class="btn-publish">
                            <i class="bi bi-megaphone me-1"></i>Publish Vacancy Notice
                        </button>
                    </form>
                </div>
            @endif
        @endif

        <div class="row g-4">
            {{-- LEFT COLUMN (col-md-8) --}}
            <div class="col-md-8">

                {{-- Agreement Details --}}
                <div class="detail-card">
                    <div class="card-header-custom">
                        <i class="bi bi-file-earmark-check"></i> Agreement Details
                    </div>
                    <div class="card-body-custom">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <div class="text-muted" style="font-size:12px;">Start Date</div>
                                <div style="font-size:14px;font-weight:600;">
                                    {{ $agreement->agreement_start_date->format('d M Y') }}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted" style="font-size:12px;">End Date</div>
                                <div style="font-size:14px;font-weight:600;">
                                    {{ $agreement->agreement_end_date->format('d M Y') }}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted" style="font-size:12px;">Duration</div>
                                <div style="font-size:14px;">1 Year</div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted" style="font-size:12px;">Amount Paid</div>
                                <div style="font-size:14px;font-weight:600;" class="text-success">
                                    RM {{ number_format($agreement->payment?->amount, 2) }}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted" style="font-size:12px;">Payment Date</div>
                                <div style="font-size:14px;">
                                    {{ $agreement->payment?->payment_date->format('d M Y') }}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted" style="font-size:12px;">Payment Method</div>
                                <div style="font-size:14px;">Card Payment</div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted" style="font-size:12px;">Card Number</div>
                                <div style="font-size:14px;">{{ $agreement->payment?->masked_card_number }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tenant Information --}}
                <div class="detail-card">
                    <div class="card-header-custom">
                        <i class="bi bi-person"></i> Tenant Information
                    </div>
                    <div class="card-body-custom">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">Full Name</div>
                                <div style="font-size:14px;font-weight:600;">
                                    {{ $agreement->application?->resident?->full_name }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">IC Number</div>
                                <div style="font-size:14px;">
                                    {{ $agreement->application?->resident?->resident_ic_number }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">Phone</div>
                                <div style="font-size:14px;">
                                    {{ $agreement->application?->resident?->resident_phone }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">Email</div>
                                <div style="font-size:14px;">
                                    {{ $agreement->application?->resident?->resident_email }}
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="text-muted" style="font-size:12px;">Business Type</div>
                                <div style="font-size:14px;">
                                    {{ $agreement->application?->intended_business_type }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN (col-md-4) --}}
            <div class="col-md-4">

                {{-- Premises Information --}}
                <div class="detail-card">
                    <div class="card-header-custom">
                        <i class="bi bi-building"></i> Premises
                    </div>
                    <div class="card-body-custom">
                        <div class="mb-3">
                            <div class="text-muted" style="font-size:12px;">Name</div>
                            <div class="fw-semibold" style="font-size:14px;">
                                {{ $agreement->application?->premises?->premises_name }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted" style="font-size:12px;">Type</div>
                            <div style="font-size:14px;">
                                <span class="premises-badge">{{ $agreement->application?->premises?->type_label }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted" style="font-size:12px;">Location</div>
                            <div style="font-size:14px;">
                                <i class="bi bi-geo-alt"></i>
                                {{ $agreement->application?->premises?->location?->location_name }}
                            </div>
                        </div>
                        <div>
                            <div class="text-muted" style="font-size:12px;">Monthly Fee</div>
                            <div class="fw-bold text-success" style="font-size:18px;">
                                RM {{ number_format($agreement->application?->premises?->rental_fee, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Terminate Agreement Button (only if active) --}}
                @if ($agreement->agreement_status === 'active')
                    <div class="terminate-card">
                        <div class="terminate-header">
                            <i class="bi bi-exclamation-triangle"></i> Terminate Agreement
                        </div>
                        <div class="terminate-body">
                            <p class="text-muted mb-3" style="font-size:12px;">
                                Terminating this agreement will set the premises back to <strong>Available</strong>
                                automatically. The resident will no longer have an active license.
                            </p>
                            <form method="POST"
                                action="{{ route('admin.agreements.terminate', $agreement->agreement_id) }}"
                                onsubmit="return confirm('Terminate this agreement? The premises will be released and become available for new applications. This action cannot be undone.')">
                                @csrf
                                <button type="submit" class="btn-terminate">
                                    <i class="bi bi-x-octagon me-2"></i> Terminate Agreement
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
