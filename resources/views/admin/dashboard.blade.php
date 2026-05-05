@extends('layouts.admin')
@section('title', 'Dashboard')
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

        /* Stat Cards */
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

        /* Chart Card */
        .chart-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            height: 100%;
        }

        .chart-card .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 700;
            background: #fff;
            border-radius: 20px 20px 0 0;
        }

        .chart-card .card-body {
            padding: 20px;
        }

        /* Recent Applications Card */
        .recent-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            height: 100%;
        }

        .recent-card .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f0f0f0;
            background: #fff;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .recent-card .card-header h6 {
            font-size: 15px;
            font-weight: 700;
            margin: 0;
        }

        .view-all-link {
            font-size: 12px;
            font-weight: 600;
            color: #1B5E20;
            text-decoration: none;
            background: #E8F5E9;
            padding: 5px 14px;
            border-radius: 20px;
            transition: all 0.15s;
        }

        .view-all-link:hover {
            background: #1B5E20;
            color: #fff;
        }

        /* Table */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table thead th {
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border-bottom: 1px solid #f0f0f0;
            background: #fafafa;
        }

        .admin-table tbody td {
            padding: 14px 16px;
            font-size: 13px;
            border-bottom: 1px solid #f5f5f5;
            vertical-align: middle;
        }

        .admin-table tbody tr:last-child td {
            border-bottom: none;
        }

        .admin-table tbody tr:hover {
            background: #fafafa;
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

        /* Small secondary badge */
        .badge-sm {
            font-size: 11px;
            padding: 4px 12px;
            margin-left: 6px;
            vertical-align: middle;
            border-radius: 20px;
            display: inline-block;
        }

        .badge-sm.active {
            background: #E8F5E9;
            color: #2E7D32;
            border: 1px solid #4CAF50;
        }

        .badge-sm.terminated {
            background: #FFEBEE;
            color: #C62828;
            border: 1px solid #EF5350;
        }

        .badge-sm.expired {
            background: #F5F5F5;
            color: #757575;
            border: 1px solid #BDBDBD;
        }

        .empty-state {
            text-align: center;
            padding: 48px;
            color: #aaa;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: 1fr;
            }

            .admin-table thead th,
            .admin-table tbody td {
                padding: 10px 12px;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">Admin Dashboard</div>
            <div class="page-sub">Overview of system activity and statistics</div>
        </div>
    </div>

    @include('partials.flash')

    {{-- Stat Cards --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon" style="background:#E3F2FD;color:#1565C0;">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalApplications }}</div>
                <div class="stat-label">Total Applications</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFF8E1;color:#F57F17;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="stat-number">{{ $pendingApplications }}</div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#E8F5E9;color:#2E7D32;">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalResidents }}</div>
                <div class="stat-label">Registered Residents</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#E8F5E9;color:#1B5E20;">
                <i class="bi bi-file-earmark-check"></i>
            </div>
            <div>
                <div class="stat-number">{{ $activeAgreements }}</div>
                <div class="stat-label">Active Agreements</div>
            </div>
        </div>
    </div>

    {{-- Chart + Recent Applications --}}
    <div class="row g-3">
        <div class="col-md-5">
            <div class="chart-card">
                <div class="card-header">Applications (Last 7 Days)</div>
                <div class="card-body">
                    <canvas id="appChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="recent-card">
                <div class="card-header">
                    <h6><i class="bi bi-clock-history me-2"></i>Recent Applications</h6>
                    <a href="{{ route('admin.applications.index') }}" class="view-all-link">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($recentApplications->isEmpty())
                        <div class="empty-state">
                            <i class="bi bi-inbox" style="font-size: 36px; opacity: 0.3;"></i>
                            <p class="mt-2 mb-0">No applications yet.</p>
                        </div>
                    @else
                        <div style="overflow-x: auto;">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">ID</th>
                                        <th>Resident</th>
                                        <th>Premises</th>
                                        <th>Status</th>
                                        <th style="width: 100px;">Date</th>
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

                                            // Check if approved application has a rental agreement
                                            $hasAgreement =
                                                $app->application_status === 'approved' && $app->rentalAgreement;
                                            $agreementStatus = $hasAgreement
                                                ? $app->rentalAgreement->agreement_status
                                                : null;
                                        @endphp
                                        <tr>
                                            <td class="text-muted fw-semibold">#{{ $app->application_id }}</td>
                                            <td>
                                                <div style="font-weight: 600;">{{ $app->resident?->full_name ?? '—' }}</div>
                                                <div style="font-size: 11px; color: #aaa;">
                                                    {{ $app->resident?->resident_email ?? '—' }}</div>
                                            </td>
                                            <td>
                                                <div>{{ $app->premises?->premises_name ?? '—' }}</div>
                                                <div style="font-size: 11px; color: #aaa;">
                                                    <i class="bi bi-geo-alt"></i>
                                                    {{ $app->premises?->location?->location_name ?? '—' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap align-items-center gap-1">
                                                    <span class="badge-pill {{ $statusClass }}">
                                                        {{ ucfirst($app->application_status) }}
                                                    </span>
                                                    @if ($hasAgreement)
                                                        @if ($agreementStatus === 'active')
                                                            <span class="badge-sm active">
                                                                 Active
                                                            </span>
                                                        @else
                                                            <span class="badge-sm {{ $agreementStatus }}">
                                                                {{ ucfirst($agreementStatus) }}
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-muted" style="white-space: nowrap;">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $app->application_date->format('d M Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const rawData = @json($applicationsByDay);

        // Build last 7 day labels (original format: YYYY-MM-DD, displayed as MM-DD)
        const days = [];
        for (let i = 6; i >= 0; i--) {
            const d = new Date();
            d.setDate(d.getDate() - i);
            days.push(d.toISOString().slice(0, 10));
        }

        const statuses = ['pending', 'approved', 'rejected', 'cancelled'];
        const colors = ['#f9a825', '#4caf50', '#ef5350', '#ab47bc'];

        const datasets = statuses.map((s, idx) => ({
            label: s.charAt(0).toUpperCase() + s.slice(1),
            backgroundColor: colors[idx],
            data: days.map(day => {
                const match = rawData.find(r => r.day === day && r.application_status === s);
                return match ? match.total : 0;
            }),
        }));

        new Chart(document.getElementById('appChart'), {
            type: 'bar',
            data: {
                labels: days.map(d => d.slice(5)), // Show MM-DD
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 11
                            },
                            boxWidth: 10,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: '#f0f0f0'
                        }
                    },
                },
            },
        });
    </script>
@endsection
