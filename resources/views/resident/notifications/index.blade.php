@extends('layouts.resident')
@section('title', 'Notifications')
@section('content')

    <style>
        .page-header {
            margin-bottom: 28px;
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

        /* Notification Card */
        .notification-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .notification-unread {
            border-left: 4px solid;
            background: #fff;
        }

        /* Badges */
        .badge-new {
            background: #dc3545;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .badge-announcement {
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .badge-terminated {
            background: #FFEBEE;
            color: #C62828;
            border: 1px solid #EF5350;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        /* Action Link */
        .action-link {
            text-decoration: none;
            font-weight: 600;
            font-size: 12px;
            transition: opacity 0.15s;
        }

        .action-link:hover {
            opacity: 0.8;
            text-decoration: underline;
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
            margin-bottom: 8px;
        }

        .empty-state .empty-hint {
            font-size: 13px;
            color: #aaa;
        }

        /* Mark All Read Button */
        .btn-mark-read {
            background: #f8f9fa;
            color: #555;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.15s;
        }

        .btn-mark-read:hover {
            background: #e9ecef;
            border-color: #ccc;
            color: #333;
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 24px;
            display: flex;
            justify-content: center;
        }
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">Notifications</div>
            <div class="page-sub">Your personal updates and MDCH announcements</div>
        </div>
        @if ($notifications->total() > 0)
            <form method="POST" action="{{ route('resident.notifications.markAllRead') }}">
                @csrf
                <button type="submit" class="btn-mark-read">
                    <i class="bi bi-check2-all me-1"></i>Mark All as Read
                </button>
            </form>
        @endif
    </div>

    @if ($notifications->isEmpty())
        <div class="empty-state">
            <i class="bi bi-bell-slash"></i>
            <p>No notifications yet.</p>
            <p class="empty-hint">You'll be notified here when your application is reviewed or new premises become
                available.</p>
        </div>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach ($notifications as $n)
                @php
                    $isUnread = !$n->is_read;
                    $cardClass = $isUnread ? 'notification-card notification-unread' : 'notification-card';
                    $borderLeftColor = $isUnread ? $n->color : 'transparent';
                @endphp
                <div class="{{ $cardClass }}" style="{{ $isUnread ? 'border-left-color: ' . $n->color . ';' : '' }}">
                    <div class="d-flex gap-3 p-4">
                        {{-- Icon --}}
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; background: {{ $n->bg }}; color: {{ $n->color }};">
                                <i class="bi {{ $n->icon }}" style="font-size: 20px;"></i>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="fw-bold" style="font-size: 15px; color: #1a1a1a;">{{ $n->title }}</span>
                                @if ($isUnread)
                                    <span class="badge-new">
                                        <i class="bi bi-circle-fill"
                                            style="font-size: 6px; vertical-align: middle; margin-right: 4px;"></i>
                                        New
                                    </span>
                                @endif
                                @if (is_null($n->resident_id))
                                    <span class="badge-announcement">
                                        <i class="bi bi-megaphone me-1"></i>Announcement
                                    </span>
                                @endif
                                @if ($n->type === 'agreement_terminated')
                                    <span class="badge-terminated">
                                        <i class="bi bi-exclamation-triangle-fill me-1" style="font-size: 9px;"></i>
                                        Terminated
                                    </span>
                                @endif
                            </div>
                            <p class="mb-2" style="font-size: 14px; color: #555; line-height: 1.5;">{{ $n->message }}
                            </p>
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <span class="text-muted" style="font-size: 12px;">
                                    <i class="bi bi-clock me-1"></i>{{ $n->created_at->diffForHumans() }}
                                </span>

                                {{-- Link to related record using proper FK columns --}}
                                @if ($n->related_application_id)
                                    @if (in_array($n->type, [
                                            'application_approved',
                                            'application_rejected',
                                            'application_cancelled',
                                            'agreement_terminated',
                                        ]))
                                        <a href="{{ route('resident.applications.show', $n->related_application_id) }}"
                                            class="action-link" style="color: {{ $n->color }};">
                                            View Application <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    @endif
                                @endif

                                @if ($n->related_premises_id)
                                    @if (in_array($n->type, ['vacancy_announcement', 'vacancy_updated', 'premises_updated']))
                                        <a href="{{ route('resident.premises.index') }}" class="action-link"
                                            style="color: {{ $n->color }};">
                                            Browse Premises <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Unread dot indicator (desktop only) --}}
                        @if ($isUnread)
                            <div class="flex-shrink-0 d-none d-md-flex align-items-center">
                                <div class="rounded-circle"
                                    style="width: 8px; height: 8px; background: {{ $n->color }};"></div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($notifications->hasPages())
            <div class="pagination-wrapper">
                {{ $notifications->links() }}
            </div>
        @endif
    @endif
@endsection
