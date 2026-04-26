@extends('layouts.resident')
@section('title', 'Browse Premises')
@section('content')

    <style>
        /* Page */
        .premises-page {
            background: #f0f0f0;
            min-height: 100vh;
        }

        /* Header Section */
        .premises-header {
            margin-bottom: 32px;
        }

        .premises-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .premises-sub {
            font-size: 14px;
            color: #777;
        }

        /* Filter Card */
        .filter-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8e8e8;
            margin-bottom: 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .filter-card-body {
            padding: 20px 24px;
        }

        .filter-label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-select {
            width: 100%;
            padding: 10px 14px;
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
            background-color: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.2s;
        }

        .btn-filter:hover {
            background-color: #154d1a;
            color: #fff;
        }

        .btn-clear {
            background: #f5f5f5;
            color: #666;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-clear:hover {
            background: #eee;
            color: #333;
        }

        /* Premises Grid */
        .premises-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .premises-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
        }

        .premises-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .premises-card-header {
            padding: 16px 20px;
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .premises-type-badge {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 10px;
            border-radius: 20px;
            background: #E8F5E9;
            color: #2E7D32;
        }

        .premises-status-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            background: #E8F5E9;
            color: #2E7D32;
        }

        .premises-card-body {
            padding: 20px;
            flex: 1;
        }

        .premises-name {
            font-size: 18px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .premises-location {
            font-size: 13px;
            color: #888;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .premises-location i {
            font-size: 13px;
            color: #aaa;
        }

        .premises-description {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .premises-card-footer {
            padding: 16px 20px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .rental-fee {
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .rental-amount {
            font-size: 20px;
            font-weight: 800;
            color: #1B5E20;
        }

        .rental-period {
            font-size: 11px;
            color: #aaa;
        }

        .btn-view-details {
            background: none;
            border: 1.5px solid #1B5E20;
            border-radius: 12px;
            padding: 8px 20px;
            font-size: 12px;
            font-weight: 600;
            color: #1B5E20;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-view-details:hover {
            background: #1B5E20;
            color: #fff;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 24px;
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
        }

        .empty-state i {
            font-size: 56px;
            opacity: 0.3;
            color: #888;
            display: block;
            margin-bottom: 16px;
        }

        .empty-state p {
            font-size: 14px;
            color: #888;
            margin-bottom: 0;
        }

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .premises-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .premises-card-header,
            .premises-card-body,
            .premises-card-footer {
                padding: 14px 16px;
            }

            .premises-name {
                font-size: 16px;
            }
        }
    </style>

    <div class="premises-page">

        {{-- Header Section --}}
        <div class="premises-header">
            <div class="premises-title">Available Premises</div>
            <div class="premises-sub">Browse MDCH-managed business premises available for rental in Cameron Highlands</div>
        </div>

        {{-- Filter Card --}}
        <div class="filter-card">
            <div class="filter-card-body">
                <form method="GET" action="{{ route('resident.premises.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="filter-label">
                                <i class="bi bi-geo-alt me-1"></i> Location
                            </label>
                            <select name="location_id" class="filter-select">
                                <option value="">All Locations</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->location_id }}"
                                        {{ request('location_id') == $loc->location_id ? 'selected' : '' }}>
                                        {{ $loc->location_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">
                                <i class="bi bi-tag me-1"></i> Premises Type
                            </label>
                            <select name="premises_type" class="filter-select">
                                <option value="">All Types</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}"
                                        {{ request('premises_type') == $type ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel"></i> Apply Filters
                            </button>
                            <a href="{{ route('resident.premises.index') }}" class="btn-clear">
                                <i class="bi bi-x-lg"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('partials.flash')

        {{-- Premises Grid --}}
        @if ($premises->isEmpty())
            <div class="empty-state">
                <i class="bi bi-building-x"></i>
                <p>No available premises found for the selected filters.</p>
            </div>
        @else
            <div class="premises-grid">
                @foreach ($premises as $p)
                    <div class="premises-card">
                        <div class="premises-card-header">
                            <span class="premises-type-badge">
                                <i class="bi bi-building me-1" style="font-size:10px;"></i>
                                {{ $p->type_label }}
                            </span>
                            <span class="premises-status-badge">
                                <i class="bi bi-check-circle-fill me-1" style="font-size:9px;"></i>
                                Available
                            </span>
                        </div>
                        <div class="premises-card-body">
                            <div class="premises-name">{{ $p->premises_name }}</div>
                            <div class="premises-location">
                                <i class="bi bi-geo-alt"></i>
                                {{ $p->location->location_name }}, Cameron Highlands
                            </div>
                            @if ($p->premises_description)
                                <div class="premises-description">
                                    {{ Str::limit($p->premises_description, 100) }}
                                </div>
                            @endif
                        </div>
                        <div class="premises-card-footer">
                            <div class="rental-fee">
                                <span class="rental-amount">RM {{ number_format($p->rental_fee, 2) }}</span>
                                <span class="rental-period">/month</span>
                            </div>
                            <a href="{{ route('resident.premises.show', $p->premises_id) }}" class="btn-view-details">
                                View Details <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $premises->withQueryString()->links() }}
            </div>
        @endif

    </div>

@endsection
