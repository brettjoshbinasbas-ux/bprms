@extends('layouts.admin')
@section('title', $resident->full_name)
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
            margin-bottom: 28px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Cards */
        .detail-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            height: 100%;
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

        /* Profile rows */
        .profile-row {
            margin-bottom: 16px;
        }

        .profile-row:last-child {
            margin-bottom: 0;
        }

        .profile-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 4px;
        }

        .profile-value {
            font-size: 14px;
            font-weight: 500;
            color: #1a1a1a;
        }

        .profile-value i {
            margin-right: 4px;
            color: #666;
        }

        /* Badges */
        .license-badge-yes {
            background: #E8F5E9;
            color: #2E7D32;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .license-badge-no {
            background: #F5F5F5;
            color: #757575;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .business-badge {
            background: #E3F2FD;
            color: #1565C0;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        /* Applications Table */
        .applications-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            height: 100%;
        }

        .applications-table {
            width: 100%;
            border-collapse: collapse;
        }

        .applications-table thead tr {
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
        }

        .applications-table thead th {
            text-align: left;
            padding: 14px 20px;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .applications-table tbody tr {
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s;
        }

        .applications-table tbody tr:hover {
            background: #fafafa;
        }

        .applications-table tbody tr:last-child {
            border-bottom: none;
        }

        .applications-table tbody td {
            padding: 14px 20px;
            font-size: 13px;
            vertical-align: middle;
        }

        /* Status Badges */
        .badge-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
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

        .action-view {
            color: #1B5E20;
            font-weight: 600;
            text-decoration: none;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 8px;
            transition: background 0.15s;
        }

        .action-view:hover {
            background: #E8F5E9;
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 48px;
            color: #aaa;
        }

        .empty-state i {
            font-size: 48px;
            opacity: 0.3;
            display: block;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .show-page {
                padding: 20px;
            }
        }
    </style>

    <div class="show-page">

        {{-- Back Link --}}
        <a href="{{ route('admin.residents.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> All Residents
        </a>

        {{-- Header --}}
        <div class="page-header">
            <div class="page-title">
                <i class="bi bi-person-circle" style="font-size: 32px; color: #1B5E20;"></i>
                {{ $resident->full_name }}
            </div>
        </div>

        @include('partials.flash')

        <div class="row g-4">
            {{-- LEFT COLUMN (col-md-5) --}}
            <div class="col-md-5">
                <div class="detail-card">
                    <div class="card-header-custom">
                        <i class="bi bi-person"></i> Resident Profile
                    </div>
                    <div class="card-body-custom">
                        <div class="profile-row">
                            <div class="profile-label">IC Number</div>
                            <div class="profile-value">
                                <i class="bi bi-card-text"></i> {{ $resident->resident_ic_number }}
                            </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Phone</div>
                            <div class="profile-value">
                                <i class="bi bi-telephone"></i> {{ $resident->resident_phone }}
                            </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Email</div>
                            <div class="profile-value">
                                <i class="bi bi-envelope"></i> {{ $resident->resident_email }}
                            </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Address</div>
                            <div class="profile-value">
                                <i class="bi bi-geo-alt"></i> {{ $resident->resident_address }}
                            </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Years in Cameron Highlands</div>
                            <div class="profile-value">{{ $resident->residency_duration }} years</div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Marital Status</div>
                            <div class="profile-value">{{ ucfirst($resident->marital_status) }}</div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">MDCH License Holder</div>
                            <div class="profile-value">
                                @if ($resident->mdch_license_holder)
                                    <span class="license-badge-yes">
                                        <i class="bi bi-check-circle-fill"></i> Yes
                                    </span>
                                @else
                                    <span class="license-badge-no">
                                        <i class="bi bi-x-circle-fill"></i> No
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Business Experience</div>
                            <div class="profile-value">
                                @if ($resident->business_experience)
                                    <span class="license-badge-yes">Yes</span>
                                @else
                                    <span class="license-badge-no">No</span>
                                @endif
                            </div>
                        </div>
                        @if ($resident->business_type)
                            <div class="profile-row">
                                <div class="profile-label">Business Type</div>
                                <div class="profile-value">
                                    <span class="business-badge">
                                        <i class="bi bi-briefcase"></i> {{ $resident->business_type }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        <div class="profile-row">
                            <div class="profile-label">Joined Date</div>
                            <div class="profile-value">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($resident->created_at)->format('d M Y, g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN (col-md-7) --}}
            <div class="col-md-7">
                <div class="applications-card">
                    <div class="card-header-custom">
                        <i class="bi bi-file-earmark-text"></i> Applications
                    </div>
                    <div class="card-body-custom p-0">
                        @if ($resident->applications->isEmpty())
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>No applications submitted by this resident.</p>
                            </div>
                        @else
                            <table class="applications-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Premises</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resident->applications as $app)
                                        @php
                                            $statusClass = match ($app->application_status) {
                                                'pending' => 'pending',
                                                'approved' => 'approved',
                                                'rejected' => 'rejected',
                                                'cancelled' => 'cancelled',
                                                default => 'pending',
                                            };
                                        @endphp
                                        <tr>
                                            <td class="text-muted">#{{ $app->application_id }}</td>
                                            <td>
                                                <div style="font-size: 13px;">{{ $app->premises?->premises_name ?? '—' }}
                                                </div>
                                                <div style="font-size: 11px;" class="text-muted">
                                                    <i class="bi bi-geo-alt"></i>
                                                    {{ $app->premises?->location?->location_name ?? '—' }}
                                                </div>
                                            </td>
                                            <td style="font-size: 12px;" class="text-muted">
                                                {{ \Carbon\Carbon::parse($app->application_date)->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge-pill {{ $statusClass }}">
                                                    {{ ucfirst($app->application_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.applications.show', $app->application_id) }}"
                                                    class="action-view">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
