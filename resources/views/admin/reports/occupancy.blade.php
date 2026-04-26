@extends('layouts.admin')
@section('title', 'Occupancy Report')
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

        /* Stats Cards - 4 in a row */
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
            font-size: 24px;
        }

        .stat-icon.total {
            background: #E3F2FD;
            color: #1565C0;
        }

        .stat-icon.available {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .stat-icon.occupied {
            background: #FFF8E1;
            color: #F57F17;
        }

        .stat-icon.unavailable {
            background: #F5F5F5;
            color: #757575;
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
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-badge.available {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #4CAF50;
        }

        .status-badge.occupied {
            background: #FFF8E1;
            color: #F57F17;
            border: 1px solid #F9A825;
        }

        .status-badge.unavailable {
            background: #F5F5F5;
            color: #757575;
            border: 1px solid #BDBDBD;
        }

        .count-number {
            font-weight: 700;
            font-size: 15px;
            color: #1a1a1a;
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
            <div class="page-title">Premises Occupancy Report</div>
            <div class="page-sub">Current occupancy status of all MDCH-managed premises.</div>
        </div>
    </div>

    @include('partials.flash')

    {{-- Stats Cards --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="bi bi-buildings"></i>
            </div>
            <div>
                <div class="stat-number">{{ $total }}</div>
                <div class="stat-label">Total Premises</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon available">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <div class="stat-number">{{ $available }}</div>
                <div class="stat-label">Available</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon occupied">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="stat-number">{{ $occupied }}</div>
                <div class="stat-label">Occupied</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon unavailable">
                <i class="bi bi-x-circle"></i>
            </div>
            <div>
                <div class="stat-number">{{ $unavailable }}</div>
                <div class="stat-label">Unavailable</div>
            </div>
        </div>
    </div>

    {{-- Occupancy by Type Table --}}
    <div class="report-card">
        @if ($byType->isEmpty())
            <div class="empty-state">
                <i class="bi bi-building-x"></i>
                <p>No premises data available.</p>
            </div>
        @else
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Premises Type</th>
                        <th>Status</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($byType as $row)
                        @php
                            $statusClass = match ($row->premises_status) {
                                'available' => 'available',
                                'occupied' => 'occupied',
                                'unavailable' => 'unavailable',
                                default => 'available',
                            };
                            $statusIcon = match ($row->premises_status) {
                                'available' => 'bi-check-circle-fill',
                                'occupied' => 'bi-building-fill',
                                'unavailable' => 'bi-x-circle-fill',
                                default => 'bi-circle',
                            };
                        @endphp
                        <tr>
                            <td>
                                <span class="type-badge">
                                    <i class="bi bi-tag" style="font-size: 10px;"></i>
                                    {{ ucwords(str_replace('_', ' ', $row->premises_type)) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="{{ $statusIcon }}" style="font-size: 10px;"></i>
                                    {{ ucfirst($row->premises_status) }}
                                </span>
                            </td>
                            <td class="count-number">{{ $row->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
