@extends('layouts.admin')
@section('title', 'Application Statistics')
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
            font-size: 22px;
        }

        .stat-icon.badge-pending {
            background: #FFF8E1;
            color: #F57F17;
        }

        .stat-icon.badge-approved {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .stat-icon.badge-rejected {
            background: #FFEBEE;
            color: #C62828;
        }

        .stat-icon.badge-cancelled {
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

        /* Chart Card */
        .chart-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .chart-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f0f0f0;
            background: #fff;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-card-header i {
            color: #1B5E20;
        }

        .chart-card-body {
            padding: 24px;
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

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">Application Statistics</div>
            <div class="page-sub">Breakdown of applications by status and monthly trend.</div>
        </div>
    </div>

    @include('partials.flash')

    {{-- Stats Cards Row (from $byStatus) --}}
    <div class="stats-row">
        @php
            $colors = [
                'pending' => 'badge-pending',
                'approved' => 'badge-approved',
                'rejected' => 'badge-rejected',
                'cancelled' => 'badge-cancelled',
            ];
            $icons = [
                'pending' => 'hourglass-split',
                'approved' => 'check-circle',
                'rejected' => 'x-circle',
                'cancelled' => 'dash-circle',
            ];
        @endphp
        @foreach ($byStatus as $row)
            <div class="stat-card">
                <div class="stat-icon {{ $colors[$row->application_status] ?? '' }}">
                    <i class="bi bi-{{ $icons[$row->application_status] ?? 'file' }}"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $row->total }}</div>
                    <div class="stat-label">{{ ucfirst($row->application_status) }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Monthly Applications Chart (from $byMonth) --}}
    <div class="chart-card">
        <div class="chart-card-header">
            <i class="bi bi-graph-up"></i>
            Monthly Applications (Last 6 Months)
        </div>
        <div class="chart-card-body">
            <canvas id="monthlyChart" height="100"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthly = @json($byMonth);
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: monthly.map(r => r.month),
                datasets: [{
                    label: 'Applications',
                    data: monthly.map(r => r.total),
                    borderColor: '#1b5e20',
                    backgroundColor: 'rgba(27,94,32,0.08)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#1b5e20',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Applications: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: '#f0f0f0'
                        },
                        title: {
                            display: true,
                            text: 'Number of Applications',
                            font: {
                                size: 11
                            }
                        }
                    },
                },
            },
        });
    </script>
@endsection
