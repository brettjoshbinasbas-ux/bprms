@extends('layouts.admin')
@section('title', 'Active Agreements Report')
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
            grid-template-columns: repeat(3, 1fr);
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

        .stat-icon.revenue {
            background: #FFF8E1;
            color: #F57F17;
        }

        .stat-icon.occupancy {
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

        /* Table Card */
        .report-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table thead tr {
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
        }

        .report-table thead th {
            text-align: left;
            padding: 16px 20px;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .report-table tbody tr {
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s;
        }

        .report-table tbody tr:hover {
            background: #fafafa;
        }

        .report-table tbody tr:last-child {
            border-bottom: none;
        }

        .report-table tbody td {
            padding: 16px 20px;
            font-size: 13px;
            vertical-align: middle;
        }

        /* Status Badge */
        .badge-active {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #4CAF50;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
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

            .report-table thead th,
            .report-table tbody td {
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
            <div class="page-title">Active Agreements Report</div>
            <div class="page-sub">Live data from <code>vw_active_agreements</code></div>
        </div>
    </div>

    @include('partials.flash')

    {{-- Stats Cards --}}
    @php
        $activeCount = $agreements->count();
        $totalMonthlyRevenue = $agreements->sum('rental_fee');
        $occupiedPremises = $agreements->count();
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
            <div class="stat-icon revenue">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <div class="stat-number">RM {{ number_format($totalMonthlyRevenue, 0) }}</div>
                <div class="stat-label">Monthly Revenue</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon occupancy">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="stat-number">{{ $occupiedPremises }}</div>
                <div class="stat-label">Occupied Premises</div>
            </div>
        </div>
    </div>

    {{-- Active Agreements Table --}}
    <div class="report-card">
        @if ($agreements->isEmpty())
            <div class="empty-state">
                <i class="bi bi-file-earmark-x"></i>
                <p>No active agreements found.</p>
            </div>
        @else
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Agreement #</th>
                        <th>Resident</th>
                        <th>Premises</th>
                        <th>Location</th>
                        <th>Business Type</th>
                        <th>Monthly Fee</th>
                        <th>Start</th>
                        <th>End</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agreements as $row)
                        <tr>
                            <td class="text-muted">#{{ $row->agreement_id }}</td>
                            <td>
                                <div style="font-weight: 600;">{{ $row->resident_full_name }}</div>
                                <div style="font-size: 11px;" class="text-muted">{{ $row->resident_ic_number }}</div>
                            </td>
                            <td>{{ $row->premises_name }}</td>
                            <td>
                                <i class="bi bi-geo-alt" style="font-size: 11px;"></i>
                                {{ $row->location_name }}
                            </td>
                            <td style="font-size: 13px;">{{ $row->intended_business_type }}</td>
                            <td style="font-weight: 600; color: #1B5E20;">RM {{ number_format($row->rental_fee, 2) }}</td>
                            <td style="font-size: 12px;" class="text-muted">
                                {{ \Carbon\Carbon::parse($row->agreement_start_date)->format('d M Y') }}
                            </td>
                            <td style="font-size: 12px;" class="text-muted">
                                {{ \Carbon\Carbon::parse($row->agreement_end_date)->format('d M Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($agreements->hasPages())
                <div class="pagination-wrapper">
                    {{ $agreements->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
