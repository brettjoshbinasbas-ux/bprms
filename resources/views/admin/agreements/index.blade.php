@extends('layouts.admin')
@section('title', 'Rental Agreements')
@section('content')

    <style>
        .page-header {
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 4px;
        }

        .page-sub {
            font-size: 14px;
            color: #777;
            margin: 0;
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-icon.active {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .stat-icon.expired {
            background: #F5F5F5;
            color: #757575;
        }

        .stat-icon.terminated {
            background: #FFEBEE;
            color: #C62828;
        }

        .stat-icon.total {
            background: #E3F2FD;
            color: #1565C0;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            color: #888;
            font-weight: 500;
        }

        /* Filter Card */
        .filter-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .filter-label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        .filter-select {
            width: 100%;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 13px;
            transition: border-color 0.2s;
        }

        .filter-select:focus {
            border-color: #1B5E20;
            outline: none;
        }

        .btn-filter {
            background: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 22px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-filter:hover {
            background: #154d1a;
        }

        .btn-clear {
            background: #fff;
            color: #555;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.15s;
        }

        .btn-clear:hover {
            background: #f5f5f5;
            border-color: #ccc;
        }

        /* Table Card */
        .agreements-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .agreements-table {
            width: 100%;
            border-collapse: collapse;
        }

        .agreements-table thead tr {
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
        }

        .agreements-table thead th {
            text-align: left;
            padding: 16px 20px;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .agreements-table tbody tr {
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s;
        }

        .agreements-table tbody tr:hover {
            background: #fafafa;
        }

        .agreements-table tbody tr:last-child {
            border-bottom: none;
        }

        .agreements-table tbody td {
            padding: 16px 20px;
            font-size: 13px;
            vertical-align: middle;
        }

        /* Status Badges */
        .badge-pill {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
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

        /* Action */
        .action-view {
            color: #1B5E20;
            font-weight: 600;
            text-decoration: none;
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background 0.15s;
        }

        .action-view:hover {
            background: #E8F5E9;
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            color: #aaa;
        }

        .empty-state i {
            font-size: 48px;
            opacity: 0.3;
            display: block;
            margin-bottom: 12px;
        }

        .pagination-wrapper {
            padding: 16px 20px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 1024px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
        }

        @media (max-width: 900px) {

            .agreements-table thead th,
            .agreements-table tbody td {
                padding: 12px 12px;
            }
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">Rental Agreements</div>
            <div class="page-sub">View and manage all rental agreements.</div>
        </div>
    </div>

    @include('partials.flash')

    {{-- Stats Cards --}}
    @php
        $activeCount = $agreements->where('agreement_status', 'active')->count();
        $expiredCount = $agreements->where('agreement_status', 'expired')->count();
        $terminatedCount = $agreements->where('agreement_status', 'terminated')->count();
        $totalCount = $agreements->count();
    @endphp

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon active">
                <i class="bi bi-file-earmark-check"></i>
            </div>
            <div>
                <div class="stat-number">{{ $activeCount }}</div>
                <div class="stat-label">Active Agreements</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon expired">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <div class="stat-number">{{ $expiredCount }}</div>
                <div class="stat-label">Expired Agreements</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon terminated">
                <i class="bi bi-x-circle"></i>
            </div>
            <div>
                <div class="stat-number">{{ $terminatedCount }}</div>
                <div class="stat-label">Terminated Agreements</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalCount }}</div>
                <div class="stat-label">Total Agreements</div>
            </div>
        </div>
    </div>

    {{-- Status Filter --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.agreements.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Statuses</option>
                        @foreach (['active', 'expired', 'terminated'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn-filter">Apply Filter</button>
                    <a href="{{ route('admin.agreements.index') }}" class="btn-clear">Clear</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Agreements Table --}}
    <div class="agreements-card">
        <table class="agreements-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Resident</th>
                    <th>Premises</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agreements as $ag)
                    @php
                        $statusClass = match ($ag->agreement_status) {
                            'active' => 'active',
                            'expired' => 'expired',
                            'terminated' => 'terminated',
                            default => 'active',
                        };
                    @endphp
                    <tr>
                        <td class="text-muted">#{{ $ag->agreement_id }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ $ag->application?->resident?->full_name ?? '—' }}</div>
                            <div style="font-size: 11px;" class="text-muted">
                                {{ $ag->application?->resident?->resident_ic_number }}</div>
                        </td>
                        <td>
                            <div style="font-size: 13px;">{{ $ag->application?->premises?->premises_name ?? '—' }}</div>
                            <div style="font-size: 11px;" class="text-muted">
                                <i class="bi bi-geo-alt"></i>
                                {{ $ag->application?->premises?->location?->location_name ?? '—' }}
                            </div>
                        </td>
                        <td style="font-size: 12px;" class="text-muted">{{ $ag->agreement_start_date->format('d M Y') }}
                        </td>
                        <td style="font-size: 12px;" class="text-muted">{{ $ag->agreement_end_date->format('d M Y') }}</td>
                        <td style="font-weight: 600; color:#1B5E20;">RM {{ number_format($ag->payment?->amount, 2) }}</td>
                        <td>
                            <span class="badge-pill {{ $statusClass }}">
                                {{ ucfirst($ag->agreement_status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.agreements.show', $ag->agreement_id) }}" class="action-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <i class="bi bi-file-earmark-x"></i>
                            <p>No rental agreements found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($agreements->hasPages())
            <div class="pagination-wrapper">
                {{ $agreements->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
