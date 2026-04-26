@extends('layouts.resident')
@section('title', 'Dashboard')
@section('content')

    <style>
        /* Page */
        .dashboard-page {
            background: #f0f0f0;
            min-height: 100vh;
            padding: 36px 40px;
        }

        /* Welcome Section */
        .welcome-section {
            margin-bottom: 32px;
        }

        .welcome-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .welcome-sub {
            font-size: 14px;
            color: #777;
        }

        /* Stat Cards - Horizontal layout with icons */
        .stats-row {
            display: flex;
            gap: 24px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px 28px;
            flex: 1;
            min-width: 180px;
            border: 1px solid #e8e8e8;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: #E3F2FD;
            color: #1565C0;
        }

        .stat-icon.green {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .stat-icon.gold {
            background: #FFF8E1;
            color: #F57F17;
        }

        .stat-info {
            flex: 1;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #1a1a1a;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: #888;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* CTA Button */
        .cta-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .btn-apply-now {
            background-color: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 40px;
            padding: 14px 48px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.2s;
        }

        .btn-apply-now:hover {
            background-color: #154d1a;
            color: #fff;
        }

        /* Recent Applications Card */
        .applications-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8e8e8;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .applications-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .applications-card-header h5 {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .applications-card-header i {
            color: #1B5E20;
            font-size: 20px;
        }

        /* Table */
        .applications-table {
            width: 100%;
            border-collapse: collapse;
        }

        .applications-table thead tr {
            border-bottom: 1px solid #eee;
        }

        .applications-table thead th {
            font-size: 12px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 16px 24px;
            background: #fff;
        }

        .applications-table thead th i {
            margin-right: 6px;
            font-size: 12px;
            color: #aaa;
        }

        .applications-table tbody tr {
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s;
        }

        .applications-table tbody tr:last-child {
            border-bottom: none;
        }

        .applications-table tbody tr:hover {
            background: #fafafa;
        }

        .applications-table tbody td {
            padding: 16px 24px;
            font-size: 14px;
            color: #333;
            vertical-align: middle;
        }

        .td-id {
            font-weight: 700;
            color: #555;
        }

        .td-premises {
            color: #444;
        }

        .td-premises i {
            margin-right: 6px;
            font-size: 13px;
        }

        .td-date {
            color: #666;
        }

        .td-date i {
            margin-right: 4px;
            font-size: 12px;
            color: #aaa;
        }

        .td-rental {
            font-weight: 700;
            color: #1a1a1a;
        }

        .td-rental i {
            margin-right: 4px;
            font-size: 12px;
            color: #1B5E20;
        }

        .td-action a {
            font-weight: 600;
            font-size: 13px;
            color: #1B5E20;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .td-action a:hover {
            text-decoration: underline;
        }

        /* Status Badges */
        .badge-pill {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pill.pending {
            background: #FF9800;
            color: #fff;
        }

        .badge-pill.approved {
            background: #2E7D32;
            color: #fff;
        }

        .badge-pill.rejected {
            background: #C62828;
            color: #fff;
        }

        .badge-pill.cancelled {
            background: #9E9E9E;
            color: #fff;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 56px 24px;
            color: #aaa;
        }

        .empty-state i {
            font-size: 44px;
            opacity: 0.3;
            display: block;
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 14px;
            margin-bottom: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-page {
                padding: 20px;
            }

            .stats-row {
                flex-direction: column;
                gap: 16px;
            }

            .stat-card {
                padding: 16px 20px;
            }

            .applications-table thead th {
                font-size: 10px;
                padding: 12px 12px;
            }

            .applications-table tbody td {
                padding: 12px 12px;
                font-size: 12px;
            }
        }
    </style>

    <div class="dashboard-page">

        {{-- Welcome Section --}}
        <div class="welcome-section">
            <div class="welcome-title">Welcome, {{ auth('resident')->user()->resident_first_name }}!</div>
            <div class="welcome-sub">Manage your premises rental applications and track your status with MDCH.</div>
        </div>

        {{-- Stat Cards with Icons --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalApplications }}</div>
                    <div class="stat-label">Total Applications</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon gold">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-number">{{ $pendingApplications }}</div>
                    <div class="stat-label">Pending Review</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-number">{{ $approvedApplications }}</div>
                    <div class="stat-label">Approved</div>
                </div>
            </div>
        </div>

        {{-- Apply Now CTA --}}
        <div class="cta-section">
            <a href="{{ route('resident.premises.index') }}" class="btn-apply-now">
                <i class="bi bi-plus-circle"></i> Browse Available Premises →
            </a>
        </div>

        {{-- Recent Applications --}}
        <div class="applications-card">
            <div class="applications-card-header">
                <i class="bi bi-clock-history"></i>
                <h5>Recent Applications</h5>
            </div>

            @if ($recentApplications->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <p>No applications yet.</p>
                    <a href="{{ route('resident.premises.index') }}" class="btn-apply-now"
                        style="padding:10px 24px;font-size:13px;">
                        Browse Premises to Apply
                    </a>
                </div>
            @else
                <table class="applications-table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash"></i> ID</th>
                            <th><i class="bi bi-shop"></i> Premises</th>
                            <th><i class="bi bi-calendar3"></i> Application Date</th>
                            <th><i class="bi bi-flag"></i> Status</th>
                            <th><i class="bi bi-cash-stack"></i> Rental Fee</th>
                            <th><i class="bi bi-gear"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentApplications as $app)
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
                                <td class="td-id">#{{ $app->application_id }}</td>
                                <td class="td-premises">
                                    <i class="bi bi-building"></i>
                                    {{ $app->premises->premises_name ?? '—' }}
                                    <span
                                        style="font-size:12px;color:#999;">({{ $app->premises->location->location_name ?? '—' }})</span>
                                </td>
                                <td class="td-date">
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($app->application_date)->format('d M Y') }}
                                </td>
                                <td class="td-status">
                                    <span class="badge-pill {{ $statusClass }}">
                                        {{ ucfirst($app->application_status) }}
                                    </span>
                                </td>
                                <td class="td-rental">
                                    <i class="bi bi-cash-stack"></i> RM
                                    {{ number_format($app->premises->rental_fee ?? 0, 2) }}
                                </td>
                                <td class="td-action">
                                    <a href="{{ route('resident.applications.show', $app->application_id) }}">
                                        <i class="bi bi-eye"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>

@endsection
