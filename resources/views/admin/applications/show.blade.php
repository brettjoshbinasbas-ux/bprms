@extends('layouts.admin')
@section('title', 'Application #' . $application->application_id)
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

        /* Status Badges */
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

        .badge-pill.active {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #4CAF50;
        }

        .badge-pill.terminated {
            background: #FFEBEE;
            color: #C62828;
            border: 1px solid #EF5350;
        }

        .badge-pill.expired {
            background: #F5F5F5;
            color: #757575;
            border: 1px solid #BDBDBD;
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

        /* Review Panel */
        .review-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
        }

        .review-header {
            padding: 16px 24px;
            border-bottom: 1px solid #f0f0f0;
            background: #FFF8E1;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .review-header i {
            color: #F57F17;
        }

        .review-body {
            padding: 24px;
        }

        .btn-submit {
            background: #1B5E20;
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

        .btn-submit:hover {
            background: #154d1a;
        }

        /* Documents */
        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            background: #fafafa;
            border-radius: 12px;
            border: 1px solid #f0f0f0;
            margin-bottom: 8px;
        }

        .doc-item:last-child {
            margin-bottom: 0;
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

        .btn-view-doc {
            background: none;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 12px;
            color: #1B5E20;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-view-doc:hover {
            background: #E8F5E9;
            border-color: #1B5E20;
        }

        .premises-badge {
            background: #E3F2FD;
            color: #1565C0;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        /* Status row for header */
        .status-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .show-page {
                padding: 20px;
            }
        }
    </style>

    <div class="show-page">

        {{-- Back Link --}}
        <a href="{{ route('admin.applications.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> All Applications
        </a>

        {{-- Header --}}
        <div class="page-header">
            <div>
                <div class="page-title">Application #{{ $application->application_id }}</div>
                <div class="page-sub">
                    Submitted on {{ $application->application_date->format('F j, Y \a\t g:i A') }}
                </div>
            </div>
            <div class="status-row">
                @php
                    $statusClass = match ($application->application_status) {
                        'pending' => 'pending',
                        'approved' => 'approved',
                        'rejected' => 'rejected',
                        'cancelled' => 'cancelled',
                        default => 'pending',
                    };
                @endphp
                <span class="badge-pill {{ $statusClass }}">
                    {{ ucfirst($application->application_status) }}
                </span>
                @if ($application->application_status === 'approved' && $application->rentalAgreement)
                    <span class="badge-pill {{ $application->display_status_badge_class }}" style="font-size: 11px;">
                        <i class="bi bi-arrow-right-short"></i> {{ ucfirst($application->display_status) }}
                    </span>
                @endif
            </div>
        </div>

        @include('partials.flash')

        <div class="row g-4">
            {{-- LEFT COLUMN (col-md-8) --}}
            <div class="col-md-8">

                {{-- Applicant Information --}}
                <div class="detail-card">
                    <div class="card-header-custom">
                        <i class="bi bi-person"></i> Applicant Information
                    </div>
                    <div class="card-body-custom">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">Full Name</div>
                                <div style="font-size:14px;font-weight:600;">{{ $application->resident?->full_name }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">IC Number</div>
                                <div style="font-size:14px;">{{ $application->resident?->resident_ic_number }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">Phone</div>
                                <div style="font-size:14px;">{{ $application->resident?->resident_phone }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">Email</div>
                                <div style="font-size:14px;">{{ $application->resident?->resident_email }}</div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted" style="font-size:12px;">Years in Cameron Highlands</div>
                                <div style="font-size:14px;">{{ $application->resident?->residency_duration }} yrs</div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted" style="font-size:12px;">Marital Status</div>
                                <div style="font-size:14px;">{{ ucfirst($application->resident?->marital_status) }}</div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-muted" style="font-size:12px;">MDCH License Holder</div>
                                <div style="font-size:14px;">
                                    {{ $application->resident?->mdch_license_holder ? 'Yes' : 'No' }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">Business Experience</div>
                                <div style="font-size:14px;">
                                    {{ $application->resident?->business_experience ? 'Yes' : 'No' }}
                                </div>
                            </div>
                            @if ($application->resident?->business_type)
                                <div class="col-sm-6">
                                    <div class="text-muted" style="font-size:12px;">Existing Business Type</div>
                                    <div style="font-size:14px;">{{ $application->resident->business_type }}</div>
                                </div>
                            @endif
                            <div class="col-12">
                                <div class="text-muted" style="font-size:12px;">Address</div>
                                <div style="font-size:14px;">{{ $application->resident?->full_address }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Application Details --}}
                <div class="detail-card">
                    <div class="card-header-custom">
                        <i class="bi bi-file-earmark-text"></i> Application Details
                    </div>
                    <div class="card-body-custom">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">Intended Business Type</div>
                                <div style="font-size:14px;">{{ $application->intended_business_type }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size:12px;">Financial Position</div>
                                <div style="font-size:14px;font-weight:600;">RM
                                    {{ number_format($application->financial_position, 2) }}
                                </div>
                            </div>
                            @if ($application->reviewed_at)
                                <div class="col-sm-6">
                                    <div class="text-muted" style="font-size:12px;">Reviewed By</div>
                                    <div style="font-size:14px;">{{ $application->reviewer?->full_name ?? '—' }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-muted" style="font-size:12px;">Reviewed On</div>
                                    <div style="font-size:14px;">{{ $application->reviewed_at->format('d M Y, g:i A') }}
                                    </div>
                                </div>
                            @endif
                            @if ($application->remarks)
                                <div class="col-12">
                                    <div class="text-muted" style="font-size:12px;">Remarks</div>
                                    <div style="font-size:14px;" class="fst-italic">{{ $application->remarks }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Uploaded Documents --}}
                <div class="detail-card">
                    <div class="card-header-custom">
                        <i class="bi bi-paperclip"></i> Uploaded Documents
                    </div>
                    <div class="card-body-custom">
                        @if ($application->documents->isEmpty())
                            <p class="text-muted mb-0" style="font-size:13px;">No documents uploaded.</p>
                        @else
                            <div class="row g-2">
                                @foreach ($application->documents as $doc)
                                    <div class="col-sm-6">
                                        <div class="doc-item">
                                            <div class="doc-info">
                                                @if (str_contains($doc->document_type, 'photo'))
                                                    <i class="bi bi-camera"></i>
                                                @elseif(str_contains($doc->document_type, 'ic'))
                                                    <i class="bi bi-card-text"></i>
                                                @else
                                                    <i class="bi bi-file-earmark"></i>
                                                @endif
                                                <div>
                                                    <div style="font-size:12px;font-weight:600;">{{ $doc->type_label }}
                                                    </div>
                                                    <div style="font-size:11px;" class="text-muted">
                                                        {{ Str::limit($doc->document_filename, 25) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($doc->document_path) }}" target="_blank"
                                                class="btn-view-doc">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Information (if exists) --}}
                @if ($application->payment)
                    <div class="detail-card">
                        <div class="card-header-custom">
                            <i class="bi bi-receipt"></i> Payment Information
                        </div>
                        <div class="card-body-custom">
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <div class="text-muted" style="font-size:12px;">Amount</div>
                                    <div class="fw-bold text-success" style="font-size:16px;">RM
                                        {{ number_format($application->payment->amount, 2) }}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted" style="font-size:12px;">Card</div>
                                    <div style="font-size:14px;">{{ $application->payment->masked_card_number }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted" style="font-size:12px;">Date</div>
                                    <div style="font-size:14px;">
                                        {{ $application->payment->payment_date->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Rental Agreement (if exists) --}}
                @if ($application->rentalAgreement)
                    <div class="detail-card">
                        <div class="card-header-custom">
                            <i class="bi bi-file-earmark-check"></i> Rental Agreement
                        </div>
                        <div class="card-body-custom">
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <div class="text-muted" style="font-size:12px;">Agreement ID</div>
                                    <div style="font-size:14px;">#{{ $application->rentalAgreement->agreement_id }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted" style="font-size:12px;">Start Date</div>
                                    <div style="font-size:14px;">
                                        {{ $application->rentalAgreement->agreement_start_date->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted" style="font-size:12px;">End Date</div>
                                    <div style="font-size:14px;">
                                        {{ $application->rentalAgreement->agreement_end_date->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted" style="font-size:12px;">Status</div>
                                    @php
                                        $agreementStatusClass = match (
                                            $application->rentalAgreement->agreement_status
                                        ) {
                                            'active' => 'active',
                                            'terminated' => 'terminated',
                                            'expired' => 'expired',
                                            default => 'pending',
                                        };
                                    @endphp
                                    <span class="badge-pill {{ $agreementStatusClass }}" style="font-size: 11px;">
                                        {{ ucfirst($application->rentalAgreement->agreement_status) }}
                                    </span>
                                </div>
                                <div class="col-sm-4">
                                    <div class="text-muted" style="font-size:12px;">Signed At</div>
                                    <div style="font-size:14px;">
                                        {{ $application->rentalAgreement->signed_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- RIGHT COLUMN (col-md-4) --}}
            <div class="col-md-4">

                {{-- Requested Premises --}}
                <div class="detail-card">
                    <div class="card-header-custom">
                        <i class="bi bi-building"></i> Requested Premises
                    </div>
                    <div class="card-body-custom">
                        <div class="mb-3">
                            <div class="text-muted" style="font-size:12px;">Name</div>
                            <div class="fw-semibold" style="font-size:14px;">
                                {{ $application->premises?->premises_name }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted" style="font-size:12px;">Type</div>
                            <div style="font-size:14px;">
                                <span class="premises-badge">{{ $application->premises?->type_label }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted" style="font-size:12px;">Location</div>
                            <div style="font-size:14px;">
                                <i class="bi bi-geo-alt"></i> {{ $application->premises?->location?->location_name }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted" style="font-size:12px;">Monthly Rental</div>
                            <div class="fw-bold text-success" style="font-size:18px;">
                                RM {{ number_format($application->premises?->rental_fee, 2) }}
                            </div>
                        </div>
                        <div>
                            <div class="text-muted" style="font-size:12px;">Current Status</div>
                            @php
                                $premisesStatusClass = match ($application->premises?->premises_status) {
                                    'available' => 'available',
                                    'occupied' => 'occupied',
                                    'unavailable' => 'unavailable',
                                    default => 'available',
                                };
                            @endphp
                            <span class="badge-pill {{ $premisesStatusClass }}" style="font-size:11px;">
                                {{ ucfirst($application->premises?->premises_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Review Panel (only if pending) --}}
                @if ($application->application_status === 'pending')
                    <div class="review-card">
                        <div class="review-header">
                            <i class="bi bi-clipboard-check"></i> Review Application
                        </div>
                        <div class="review-body">
                            <form method="POST"
                                action="{{ route('admin.applications.review', $application->application_id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" style="font-size:13px;">Decision <span
                                            class="text-danger">*</span></label>
                                    <div class="d-flex gap-2">
                                        <div class="form-check flex-fill border rounded p-2">
                                            <input class="form-check-input" type="radio" name="decision"
                                                id="decApprove" value="approved" required>
                                            <label class="form-check-label text-success fw-semibold" for="decApprove"
                                                style="font-size:13px;">
                                                <i class="bi bi-check-circle me-1"></i>Approve
                                            </label>
                                        </div>
                                        <div class="form-check flex-fill border rounded p-2">
                                            <input class="form-check-input" type="radio" name="decision"
                                                id="decReject" value="rejected">
                                            <label class="form-check-label text-danger fw-semibold" for="decReject"
                                                style="font-size:13px;">
                                                <i class="bi bi-x-circle me-1"></i>Reject
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-size:13px;">Remarks <span
                                            class="text-muted">(Optional)</span></label>
                                    <textarea name="remarks" rows="3" class="form-control"
                                        style="font-size:13px; border-radius:12px; border:1.5px solid #e0e0e0;"
                                        placeholder="Add review notes or reasons for decision..."></textarea>
                                </div>
                                <button type="submit" class="btn-submit"
                                    onclick="return confirm('Submit this review decision?')">
                                    <i class="bi bi-send me-2"></i>Submit Review
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
