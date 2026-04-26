@extends('layouts.resident')
@section('title', $premises->premises_name)
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

        .show-header .premises-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .show-header .premises-location {
            font-size: 14px;
            color: #777;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .show-header .premises-location i {
            font-size: 14px;
            color: #aaa;
        }

        /* Status badge */
        .badge-pill {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .badge-pill.available {
            background: #2E7D32;
            color: #fff;
        }

        .badge-pill.occupied {
            background: #F57F17;
            color: #fff;
        }

        .badge-pill.unavailable {
            background: #9E9E9E;
            color: #fff;
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
            border-radius: 16px;
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
        }

        .dr-value {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .dr-value i {
            font-size: 14px;
            color: #666;
        }

        .rental-fee {
            font-size: 28px;
            font-weight: 800;
            color: #1B5E20;
            margin-top: 8px;
        }

        .rental-period {
            font-size: 12px;
            color: #888;
            font-weight: normal;
        }

        /* Application Requirements */
        .requirements-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .requirements-list li {
            padding: 8px 0;
            font-size: 13px;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #f5f5f5;
        }

        .requirements-list li:last-child {
            border-bottom: none;
        }

        .requirements-list li i {
            color: #1B5E20;
            font-size: 14px;
            width: 20px;
        }

        /* Apply button */
        .apply-section {
            margin-top: 32px;
            text-align: right;
        }

        .btn-apply {
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

        .btn-apply:hover {
            background: #154d1a;
            color: #fff;
        }

        .btn-apply-disabled {
            background: #ccc;
            color: #666;
            border: none;
            border-radius: 12px;
            padding: 14px 36px;
            font-size: 15px;
            font-weight: 700;
            cursor: not-allowed;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* Divider */
        .detail-divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 20px 0;
        }

        /* Admin notes section (if any) */
        .admin-note {
            background: #FFF8E1;
            border-left: 4px solid #F9A825;
            padding: 12px 16px;
            border-radius: 10px;
            margin-top: 16px;
        }

        .admin-note i {
            color: #F57F17;
            margin-right: 8px;
        }

        .admin-note .note-label {
            font-size: 11px;
            font-weight: 700;
            color: #F57F17;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .admin-note .note-text {
            font-size: 13px;
            color: #666;
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

            .show-header .premises-title {
                font-size: 22px;
            }

            .rental-fee {
                font-size: 24px;
            }
        }
    </style>

    <div class="show-page">

        {{-- Back --}}
        <a href="{{ route('resident.premises.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Premises
        </a>

        {{-- Header --}}
        <div class="show-header">
            <div>
                <div class="premises-title">{{ $premises->premises_name }}</div>
                <div class="premises-location">
                    <i class="bi bi-geo-alt-fill"></i>
                    {{ $premises->location->location_name }}, Cameron Highlands
                </div>
            </div>
            @php
                $statusClass = match ($premises->premises_status) {
                    'available' => 'available',
                    'occupied' => 'occupied',
                    'unavailable' => 'unavailable',
                    default => 'available',
                };
            @endphp
            <span class="badge-pill {{ $statusClass }}">{{ ucfirst($premises->premises_status) }}</span>
        </div>

        {{-- Two-column grid layout --}}
        <div class="two-column-grid">

            {{-- LEFT: Premises Details --}}
            <div class="detail-card">
                <div class="detail-card-title">
                    <i class="bi bi-building"></i> Premises Details
                </div>

                <div class="detail-row">
                    <div class="dr-label">Premises Type:</div>
                    <div class="dr-value">
                        <i class="bi bi-tag"></i>
                        {{ $premises->type_label }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="dr-label">Location:</div>
                    <div class="dr-value">
                        <i class="bi bi-geo-alt"></i>
                        {{ $premises->location->location_name }}, Cameron Highlands
                    </div>
                </div>

                @if ($premises->premises_description)
                    <div class="detail-row">
                        <div class="dr-label">Description:</div>
                        <div class="dr-value" style="font-weight: normal; line-height: 1.5;">
                            {{ $premises->premises_description }}
                        </div>
                    </div>
                @endif

                <hr class="detail-divider">

                <div class="dr-label">Monthly Rental Fee:</div>
                <div class="rental-fee">
                    RM {{ number_format($premises->rental_fee, 2) }}
                    <span class="rental-period">/month</span>
                </div>

                @if ($premises->premises_status === 'occupied')
                    <div class="admin-note">
                        <div class="note-label">
                            <i class="bi bi-info-circle"></i> Note
                        </div>
                        <div class="note-text">
                            This premises is currently occupied. Please check back later or browse other available premises.
                        </div>
                    </div>
                @endif
            </div>

            {{-- RIGHT: Application Information & Requirements --}}
            <div class="detail-card">
                <div class="detail-card-title">
                    <i class="bi bi-clipboard-check"></i> Application Requirements
                </div>

                <div class="detail-row">
                    <div class="dr-label">MDCH Requirements:</div>
                    <ul class="requirements-list">
                        <li><i class="bi bi-check-circle-fill"></i> Must be a Malaysian citizen</li>
                        <li><i class="bi bi-check-circle-fill"></i> Priority given to Pahang residents with ≥5 years
                            residency</li>
                        <li><i class="bi bi-check-circle-fill"></i> Must be 18 years or older</li>
                        <li><i class="bi bi-check-circle-fill"></i> Not a civil servant</li>
                        <li><i class="bi bi-check-circle-fill"></i> No criminal record or MDCH offense record</li>
                        <li><i class="bi bi-check-circle-fill"></i> No more than 1 active MDCH business license</li>
                    </ul>
                </div>

                <hr class="detail-divider">

                <div class="detail-row">
                    <div class="dr-label">Required Documents:</div>
                    <ul class="requirements-list">
                        <li><i class="bi bi-file-earmark-text"></i> IC Copy</li>
                        <li><i class="bi bi-camera"></i> Applicant Photo</li>
                        <li><i class="bi bi-person"></i> Spouse Photo (if applicable)</li>
                        <li><i class="bi bi-files"></i> Supporting Document (Business Registration, etc.)</li>
                    </ul>
                </div>

                <hr class="detail-divider">

                <div class="detail-row">
                    <div class="dr-label">Application Process:</div>
                    <ul class="requirements-list">
                        <li><i class="bi bi-1-circle"></i> Submit application with required documents</li>
                        <li><i class="bi bi-2-circle"></i> Admin review by Jawatankuasa Temuduga Gerai/Premis</li>
                        <li><i class="bi bi-3-circle"></i> If approved, proceed to payment</li>
                        <li><i class="bi bi-4-circle"></i> Sign rental agreement (1-year term)</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="apply-section">
            @if ($premises->premises_status === 'available')
                <a href="{{ route('resident.applications.create', ['premises_id' => $premises->premises_id]) }}"
                    class="btn-apply">
                    <i class="bi bi-file-earmark-plus"></i> Apply for This Premises
                </a>
            @else
                <span class="btn-apply-disabled">
                    <i class="bi bi-x-circle"></i> Not Available for Application
                </span>
            @endif
        </div>

    </div>

@endsection
