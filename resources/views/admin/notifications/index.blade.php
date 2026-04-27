@extends('layouts.admin')
@section('title', 'Announcements')
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

        .btn-primary-custom {
            background: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s;
        }

        .btn-primary-custom:hover {
            background: #154d1a;
        }

        /* Info Banner */
        .info-banner {
            background: #E3F2FD;
            border: 1px solid #90CAF9;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            color: #1565C0;
            font-size: 13px;
        }

        .info-banner i {
            font-size: 20px;
            flex-shrink: 0;
        }

        /* Table Card */
        .announcements-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .announcements-table {
            width: 100%;
            border-collapse: collapse;
        }

        .announcements-table thead tr {
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
        }

        .announcements-table thead th {
            text-align: left;
            padding: 16px 20px;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .announcements-table tbody tr {
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s;
        }

        .announcements-table tbody tr:hover {
            background: #fafafa;
        }

        .announcements-table tbody tr:last-child {
            border-bottom: none;
        }

        .announcements-table tbody td {
            padding: 16px 20px;
            font-size: 13px;
            vertical-align: middle;
        }

        .title-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .title-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #E3F2FD;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .title-icon i {
            font-size: 16px;
            color: #1565C0;
        }

        .title-text {
            font-weight: 600;
            font-size: 14px;
            color: #1a1a1a;
        }

        .message-preview {
            color: #666;
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .date-cell {
            font-size: 12px;
            color: #888;
            white-space: nowrap;
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

        /* Modal Styles */
        .modal-content-custom {
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
        }

        .modal-header-custom {
            padding: 18px 24px;
            border-bottom: 1px solid #f0f0f0;
            background: #fff;
        }

        .modal-title-custom {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .modal-body-custom {
            padding: 24px;
        }

        .modal-footer-custom {
            padding: 16px 24px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .type-card {
            border: 2px solid #e8e8e8;
            border-radius: 16px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.15s;
            height: 100%;
        }

        .type-card:hover {
            border-color: #c0e0c0;
            background: #fafafa;
        }

        .type-card.selected {
            border-color: #1B5E20;
            background: #E8F5E9;
        }

        .type-radio {
            margin-bottom: 8px;
        }

        .type-title {
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .type-desc {
            font-size: 11px;
            color: #888;
            margin-top: 4px;
        }

        .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        .form-input-custom,
        .form-select-custom,
        .form-textarea-custom {
            width: 100%;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-input-custom:focus,
        .form-select-custom:focus,
        .form-textarea-custom:focus {
            border-color: #1B5E20;
            outline: none;
        }

        .form-textarea-custom {
            resize: vertical;
            min-height: 100px;
        }

        .btn-cancel {
            background: #f5f5f5;
            color: #555;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.15s;
        }

        .btn-cancel:hover {
            background: #eee;
        }

        .btn-publish {
            background: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 8px 24px;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.15s;
        }

        .btn-publish:hover {
            background: #154d1a;
        }

        .btn-delete {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 8px;
            color: #C62828;
            transition: all 0.15s;
        }

        .btn-delete:hover {
            background: #FFEBEE;
        }

        @media (max-width: 768px) {
            .message-preview {
                max-width: 150px;
            }

            .date-cell {
                white-space: normal;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">Announcements</div>
            <div class="page-sub">Publish vacancy notices and general announcements to all residents</div>
        </div>
        <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            New Announcement
        </button>
    </div>

    @include('partials.flash')

    {{-- Info banner --}}
    <div class="info-banner">
        <i class="bi bi-info-circle-fill"></i>
        <div>
            All announcements are broadcast to <strong>all registered residents</strong> and appear in their
            Notifications page. Vacancy announcements are also auto-published whenever a premises is
            set to <strong>Available</strong> from the Premises management page.
        </div>
    </div>

    <div class="announcements-card">
        @if ($announcements->isEmpty())
            <div class="empty-state">
                <i class="bi bi-megaphone"></i>
                <p>No announcements published yet.</p>
            </div>
        @else
            <table class="announcements-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Published</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($announcements as $ann)
                        <tr>
                            <td>
                                <div class="title-cell">
                                    <div class="title-icon">
                                        <i class="bi bi-megaphone"></i>
                                    </div>
                                    <span class="title-text">{{ $ann->title }}</span>
                                </div>
                            </td>
                            <td class="message-preview">
                                {{ Str::limit($ann->message, 80) }}
                            </td>
                            <td class="date-cell">
                                <i class="bi bi-clock me-1"></i>
                                {{ $ann->created_at->format('d M Y, g:i A') }}
                            </td>
                            <td>
                                <form method="POST"
                                    action="{{ route('admin.notifications.destroy', $ann->notification_id) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this announcement? This action cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete" title="Delete Announcement">
                                        <i class="bi bi-trash3" style="font-size: 16px;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($announcements->hasPages())
                <div class="pagination-wrapper">
                    {{ $announcements->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- New Announcement Modal --}}
    <div class="modal fade" id="addAnnouncementModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <form method="POST" action="{{ route('admin.notifications.store') }}">
                    @csrf
                    <div class="modal-header-custom">
                        <h6 class="modal-title-custom">
                            <i class="bi bi-megaphone me-2"></i>Publish Announcement
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body-custom">

                        {{-- Type selector --}}
                        <div class="mb-4">
                            <label class="form-label-custom">Announcement Type</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="type-card" id="typeVacancyCard" onclick="selectType('vacancy')">
                                        <div class="type-radio">
                                            <input class="form-check-input" type="radio" name="announcement_type"
                                                id="typeVacancy" value="vacancy" checked>
                                        </div>
                                        <div class="type-title">
                                            <i class="bi bi-shop text-success"></i>
                                            Vacancy Notice
                                        </div>
                                        <div class="type-desc">
                                            Announce that a specific premises is available for rental.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="type-card" id="typeCustomCard" onclick="selectType('custom')">
                                        <div class="type-radio">
                                            <input class="form-check-input" type="radio" name="announcement_type"
                                                id="typeCustom" value="custom">
                                        </div>
                                        <div class="type-title">
                                            <i class="bi bi-megaphone text-primary"></i>
                                            General Announcement
                                        </div>
                                        <div class="type-desc">
                                            Send a custom message to all residents.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Vacancy fields --}}
                        <div id="vacancyFields">
                            <div class="mb-0">
                                <label class="form-label-custom">
                                    Select Premises <span class="text-danger">*</span>
                                </label>
                                <select name="premises_id" class="form-select-custom">
                                    <option value="">— Select a premises —</option>
                                    @foreach ($premises as $p)
                                        <option value="{{ $p->premises_id }}">
                                            {{ $p->premises_name }}
                                            ({{ $p->type_label }})
                                            —
                                            RM {{ number_format($p->rental_fee, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text" style="font-size: 11px; color: #aaa; margin-top: 6px;">
                                    <i class="bi bi-info-circle"></i>
                                    The announcement message will be auto-generated from the premises details.
                                </div>
                            </div>
                        </div>

                        {{-- Custom fields --}}
                        <div id="customFields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label-custom">
                                    Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" class="form-input-custom"
                                    placeholder="e.g. New Stalls Available in Brinchang">
                            </div>
                            <div class="mb-0">
                                <label class="form-label-custom">
                                    Message <span class="text-danger">*</span>
                                </label>
                                <textarea name="message" class="form-textarea-custom" rows="4" placeholder="Write your announcement here..."></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-publish">
                            <i class="bi bi-send me-1"></i>Publish to All Residents
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function selectType(type) {
            const isVacancy = type === 'vacancy';

            // Update radio buttons
            document.getElementById('typeVacancy').checked = isVacancy;
            document.getElementById('typeCustom').checked = !isVacancy;

            // Update card styles
            const vacancyCard = document.getElementById('typeVacancyCard');
            const customCard = document.getElementById('typeCustomCard');

            if (isVacancy) {
                vacancyCard.classList.add('selected');
                customCard.classList.remove('selected');
            } else {
                customCard.classList.add('selected');
                vacancyCard.classList.remove('selected');
            }

            // Toggle form fields
            document.getElementById('vacancyFields').style.display = isVacancy ? 'block' : 'none';
            document.getElementById('customFields').style.display = isVacancy ? 'none' : 'block';
        }

        // Initialize with vacancy selected
        document.addEventListener('DOMContentLoaded', function() {
            selectType('vacancy');
        });
    </script>
@endsection
