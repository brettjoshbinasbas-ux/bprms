@extends('layouts.resident')
@section('title', 'My Applications')
@section('content')

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 4px;
        }

        .page-sub {
            font-size: 14px;
            color: #777;
            margin: 0;
        }

        .btn-new {
            background: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            transition: background 0.15s;
            text-decoration: none;
        }

        .btn-new:hover {
            background: #154d1a;
            color: #fff;
        }

        /* Filter Bar */
        .filter-bar {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8e8e8;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .filter-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 8px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            font-size: 13px;
            background: #fff;
            cursor: pointer;
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
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }

        .btn-filter:hover {
            background: #154d1a;
        }

        .btn-clear {
            background: #f5f5f5;
            color: #666;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-clear:hover {
            background: #eee;
            color: #333;
        }

        /* Applications Card */
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
            padding: 18px 20px;
            font-size: 14px;
            vertical-align: middle;
        }

        .td-id {
            font-weight: 700;
            color: #1a1a1a;
        }

        .td-premises {
            font-weight: 500;
            color: #333;
        }

        .td-location {
            font-size: 13px;
            color: #888;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .td-location i {
            font-size: 12px;
        }

        .td-business-type {
            font-size: 13px;
            color: #555;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .td-date {
            font-size: 13px;
            color: #666;
            white-space: nowrap;
        }

        .td-rental {
            font-weight: 600;
            color: #1B5E20;
            white-space: nowrap;
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

        .td-action a {
            font-weight: 600;
            font-size: 13px;
            color: #1B5E20;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background 0.15s;
        }

        .td-action a:hover {
            background: #E8F5E9;
            text-decoration: none;
        }

        .btn-cancel-app {
            background: none;
            border: none;
            color: #C62828;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-cancel-app:hover {
            background: #FFEBEE;
        }

        .empty-state {
            text-align: center;
            padding: 60px 24px;
            color: #aaa;
        }

        .empty-state i {
            font-size: 56px;
            opacity: 0.3;
            display: block;
            margin-bottom: 16px;
        }

        .empty-state p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .empty-state .btn-primary {
            background: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 24px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .pagination-wrapper {
            padding: 16px 20px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .applications-table thead th {
                padding: 12px 12px;
                font-size: 10px;
            }

            .applications-table tbody td {
                padding: 12px 12px;
                font-size: 12px;
            }

            .td-business-type {
                max-width: 100px;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-controls {
                justify-content: space-between;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">My Applications</div>
            <div class="page-sub">Track and manage your premises rental applications</div>
        </div>
        <a href="{{ route('resident.premises.index') }}" class="btn-new">
            <i class="bi bi-plus-circle"></i> New Application
        </a>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('resident.applications.index') }}" class="filter-controls">
            <select name="status" class="filter-select">
                <option value="">All Statuses</option>
                @foreach (['pending', 'approved', 'rejected', 'cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn-filter">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <a href="{{ route('resident.applications.index') }}" class="btn-clear">
                <i class="bi bi-x-lg"></i> Clear
            </a>
        </form>
    </div>

    @include('partials.flash')

    {{-- Applications Table --}}
    <div class="applications-card">
        @if ($applications->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <p>No applications found.</p>
                <a href="{{ route('resident.premises.index') }}" class="btn-primary">
                    <i class="bi bi-shop"></i> Browse Premises to Apply
                </a>
            </div>
        @else
            <table class="applications-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Premises</th>
                        <th>Location</th>
                        <th>Business Type</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                        <th>Rental Fee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $app)
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
                                <i class="bi bi-building" style="color:#888; margin-right: 6px;"></i>
                                {{ $app->premises->premises_name ?? '—' }}
                            </td>
                            <td class="td-location">
                                <i class="bi bi-geo-alt"></i>
                                {{ $app->premises->location->location_name ?? '—' }}
                            </td>
                            <td class="td-business-type" title="{{ $app->intended_business_type }}">
                                {{ Str::limit($app->intended_business_type, 30) }}
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
                                <i class="bi bi-cash-stack"></i>
                                RM {{ number_format($app->premises->rental_fee ?? 0, 2) }}
                            </td>
                            <td class="td-action">
                                <a href="{{ route('resident.applications.show', $app->application_id) }}">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                @if ($app->application_status === 'pending')
                                    <form method="POST"
                                        action="{{ route('resident.applications.cancel', $app->application_id) }}
"
                                        class="d-inline"
                                        onsubmit="return confirm('Cancel this application? This action cannot be undone.')">
                                        @csrf
                                        <button type="submit" class="btn-cancel-app">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($applications->hasPages())
                <div class="pagination-wrapper">
                    {{ $applications->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>

@endsection
