@extends('layouts.admin')
@section('title', 'All Applications')
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

        .filter-select,
        .filter-input {
            width: 100%;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 13px;
            transition: border-color 0.2s;
        }

        .filter-select:focus,
        .filter-input:focus {
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
        .applications-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
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
            padding: 16px 20px;
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
            padding: 16px 20px;
            font-size: 14px;
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

        @media (max-width: 900px) {

            .applications-table thead th,
            .applications-table tbody td {
                padding: 12px 12px;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">All Applications</div>
            <div class="page-sub">Review and manage resident rental applications.</div>
        </div>
    </div>

    @include('partials.flash')

    {{-- Filters --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.applications.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Statuses</option>
                        @foreach (['pending', 'approved', 'rejected', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="filter-label">Search Resident</label>
                    <input type="text" name="search" class="filter-input" placeholder="Name, IC number, or email..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn-filter">Apply Filters</button>
                    <a href="{{ route('admin.applications.index') }}" class="btn-clear">Clear</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Applications Table --}}
    <div class="applications-card">
        <table class="applications-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Resident</th>
                    <th>Premises</th>
                    <th>Business Type</th>
                    <th>Applied</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
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
                            <div style="font-weight: 600;">{{ $app->resident?->full_name ?? '—' }}</div>
                            <div style="font-size: 11px;" class="text-muted">{{ $app->resident?->resident_email }}</div>
                        </td>
                        <td>
                            <div style="font-size: 13px;">{{ $app->premises?->premises_name ?? '—' }}</div>
                            <div style="font-size: 11px;" class="text-muted">
                                <i class="bi bi-geo-alt"></i> {{ $app->premises?->location?->location_name ?? '—' }}
                            </div>
                        </td>
                        <td style="font-size: 13px;">{{ Str::limit($app->intended_business_type, 35) }}</td>
                        <td style="font-size: 12px;" class="text-muted">{{ $app->application_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge-pill {{ $statusClass }}">
                                {{ ucfirst($app->application_status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.applications.show', $app->application_id) }}" class="action-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p>No applications found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($applications->hasPages())
            <div class="pagination-wrapper">
                {{ $applications->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
