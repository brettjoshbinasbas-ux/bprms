@extends('layouts.admin')
@section('title', 'Revenue Summary')
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

        .stat-icon.total {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .stat-icon.transactions {
            background: #E3F2FD;
            color: #1565C0;
        }

        .stat-icon.avg {
            background: #FFF8E1;
            color: #F57F17;
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

        /* Premises Type Badge */
        .type-badge {
            background: #E3F2FD;
            color: #1565C0;
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
            <div class="page-title">Revenue Summary</div>
            <div class="page-sub">Completed payments grouped by location and premises type — <code>vw_revenue_summary</code>
            </div>
        </div>
    </div>

    @include('partials.flash')

    {{-- Stats Cards --}}
    @php
        $totalRevenue = $summary->sum('total_revenue');
        $totalPayments = $summary->sum('total_payments');
        $avgRevenue = $totalPayments > 0 ? $totalRevenue / $summary->count() : 0;
    @endphp

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <div class="stat-number">RM {{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon transactions">
                <i class="bi bi-credit-card"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalPayments }}</div>
                <div class="stat-label">Total Payments</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon avg">
                <i class="bi bi-graph-up"></i>
            </div>
            <div>
                <div class="stat-number">RM {{ number_format($avgRevenue, 2) }}</div>
                <div class="stat-label">Average / Type</div>
            </div>
        </div>
    </div>

    {{-- Revenue Summary Table --}}
    <div class="report-card">
        @if ($summary->isEmpty())
            <div class="empty-state">
                <i class="bi bi-cash-stack"></i>
                <p>No revenue data found.</p>
            </div>
        @else
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Premises Type</th>
                        <th>Total Payments</th>
                        <th>Total Revenue</th>
                        <th>Average Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($summary as $row)
                        <tr>
                            <td style="font-weight: 600;">
                                <i class="bi bi-geo-alt-fill" style="color: #1B5E20; margin-right: 6px;"></i>
                                {{ $row->location_name }}
                            </td>
                            <td>
                                <span class="type-badge">
                                    {{ ucwords(str_replace('_', ' ', $row->premises_type)) }}
                                </span>
                            </td>
                            <td style="font-weight: 600;">{{ number_format($row->total_payments) }}</td>
                            <td style="font-weight: 700; color: #1B5E20;">RM {{ number_format($row->total_revenue, 2) }}
                            </td>
                            <td class="text-muted">RM {{ number_format($row->average_payment, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tr>
        @endif
    </div>
@endsection
