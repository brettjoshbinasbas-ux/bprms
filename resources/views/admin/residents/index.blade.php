@extends('layouts.admin')
@section('title', 'Residents')
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

        .stat-icon.total {
            background: #E3F2FD;
            color: #1565C0;
        }

        .stat-icon.active {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .stat-icon.license {
            background: #FFF8E1;
            color: #F57F17;
        }

        .stat-icon.new {
            background: #E8F5E9;
            color: #1B5E20;
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

        .filter-input {
            width: 100%;
            max-width: 320px;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 13px;
            transition: border-color 0.2s;
        }

        .filter-input:focus {
            border-color: #1B5E20;
            outline: none;
        }

        .btn-search {
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

        .btn-search:hover {
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
        .residents-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .residents-table {
            width: 100%;
            border-collapse: collapse;
        }

        .residents-table thead tr {
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
        }

        .residents-table thead th {
            text-align: left;
            padding: 16px 20px;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .residents-table tbody tr {
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s;
        }

        .residents-table tbody tr:hover {
            background: #fafafa;
        }

        .residents-table tbody tr:last-child {
            border-bottom: none;
        }

        .residents-table tbody td {
            padding: 16px 20px;
            font-size: 13px;
            vertical-align: middle;
        }

        /* Status Badges */
        .application-badge {
            background: #E8F5E9;
            color: #1B5E20;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .marital-badge {
            background: #F5F5F5;
            color: #757575;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
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

            .residents-table thead th,
            .residents-table tbody td {
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
            <div class="page-title">Residents</div>
            <div class="page-sub">View all registered resident accounts.</div>
        </div>
    </div>

    @include('partials.flash')

    {{-- Stats Cards --}}
    @php
        $totalResidents = $residents->total();
        $activeResidents = $residents->where('applications_count', '>', 0)->count();
        $licenseHolders = $residents->where('mdch_license_holder', true)->count();
        $newResidents = $residents->where('created_at', '>=', now()->subDays(30))->count();
    @endphp

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalResidents }}</div>
                <div class="stat-label">Total Residents</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <div class="stat-number">{{ $activeResidents }}</div>
                <div class="stat-label">Active (Has Applications)</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon license">
                <i class="bi bi-file-earmark-check"></i>
            </div>
            <div>
                <div class="stat-number">{{ $licenseHolders }}</div>
                <div class="stat-label">MDCH License Holders</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon new">
                <i class="bi bi-star"></i>
            </div>
            <div>
                <div class="stat-number">{{ $newResidents }}</div>
                <div class="stat-label">New (Last 30 Days)</div>
            </div>
        </div>
    </div>

    {{-- Search Filter --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.residents.index') }}"
            class="d-flex gap-2 align-items-center flex-wrap">
            <input type="text" name="search" class="filter-input" placeholder="Search by name, IC, or email..."
                value="{{ request('search') }}">
            <button type="submit" class="btn-search">Search</button>
            <a href="{{ route('admin.residents.index') }}" class="btn-clear">Clear</a>
        </form>
    </div>

    {{-- Residents Table --}}
    <div class="residents-card">
        <table class="residents-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>IC Number</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Applications</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($residents as $r)
                    <tr>
                        <td class="text-muted">{{ $r->resident_id }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ $r->full_name }}</div>
                            <div style="font-size: 11px;" class="text-muted">
                                <span class="marital-badge">{{ ucfirst($r->marital_status) }}</span>
                            </div>
                        </td>
                        <td style="font-family: monospace; font-size: 13px;">{{ $r->resident_ic_number }}</td>
                        <td style="font-size: 13px;">{{ $r->resident_phone }}</td>
                        <td style="font-size: 13px;">{{ $r->resident_email }}</td>
                        <td>
                            <span class="application-badge">
                                <i class="bi bi-file-earmark-text" style="font-size: 10px;"></i>
                                {{ $r->applications_count }} applications
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.residents.show', $r->resident_id) }}" class="action-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="bi bi-people"></i>
                            <p>No residents found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($residents->hasPages())
            <div class="pagination-wrapper">
                {{ $residents->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
