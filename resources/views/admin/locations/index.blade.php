@extends('layouts.admin')
@section('title', 'Manage Locations')
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

        .btn-add {
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

        .btn-add:hover {
            background: #154d1a;
        }

        /* Warning Banner for Force Delete */
        .warning-banner {
            background: #FFF8E1;
            border: 1px solid #FFA726;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .warning-banner i {
            color: #F57F17;
            font-size: 20px;
        }

        .warning-banner .warning-text {
            flex: 1;
        }

        .btn-force-delete {
            background: #C62828;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
            white-space: nowrap;
        }

        .btn-force-delete:hover {
            background: #b71c1c;
        }

        .btn-cancel-warning {
            background: #f5f5f5;
            color: #555;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-cancel-warning:hover {
            background: #eee;
        }

        /* Table Card */
        .locations-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .locations-table {
            width: 100%;
            border-collapse: collapse;
        }

        .locations-table thead tr {
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
        }

        .locations-table thead th {
            text-align: left;
            padding: 16px 20px;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .locations-table tbody tr {
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s;
        }

        .locations-table tbody tr:hover {
            background: #fafafa;
        }

        .locations-table tbody tr:last-child {
            border-bottom: none;
        }

        .locations-table tbody td {
            padding: 16px 20px;
            font-size: 14px;
            vertical-align: middle;
        }

        .premises-count-badge {
            background: #E8F5E9;
            color: #2E7D32;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        /* Action Buttons */
        .actions-cell {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
            font-size: 16px;
        }

        .icon-btn.edit {
            color: #1B5E20;
        }

        .icon-btn.edit:hover {
            background: #E8F5E9;
        }

        .icon-btn.del {
            color: #C62828;
        }

        .icon-btn.del:hover {
            background: #FFEBEE;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 20px;
            border: 1px solid #e8e8e8;
        }

        .modal-header {
            border-bottom: 1px solid #f0f0f0;
            padding: 18px 24px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border-top: 1px solid #f0f0f0;
            padding: 16px 24px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }

        .modal-input {
            width: 100%;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .modal-input:focus {
            border-color: #1B5E20;
            outline: none;
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

        @media (max-width: 768px) {

            .locations-table thead th,
            .locations-table tbody td {
                padding: 12px 12px;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">Manage Locations</div>
            <div class="page-sub">Add, edit, or remove Cameron Highlands locations.</div>
        </div>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addLocationModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Add Location
        </button>
    </div>

    @include('partials.flash')

    {{-- Force Delete Warning Banner --}}
    @if (session('warning') && str_contains(session('warning'), 'location_force_delete_id='))
        @php
            preg_match('/location_force_delete_id=(\d+)/', session('warning'), $m);
            $fdId = $m[1] ?? null;
            // Strip the token from the display message
            $warnMsg = preg_replace('/\s*location_force_delete_id=\d+/', '', session('warning'));
        @endphp
        <div class="warning-banner">
            <div class="warning-text">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Warning:</strong> {{ $warnMsg }}
            </div>
            @if ($fdId)
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('admin.locations.destroy', $fdId) }}?confirm=1">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-force-delete"
                            onclick="return confirm('This will permanently delete this location and ALL its premises. This action cannot be undone. Are you sure?')">
                            <i class="bi bi-trash me-1"></i>Force Delete Location & All Premises
                        </button>
                    </form>
                    <a href="{{ route('admin.locations.index') }}" class="btn-cancel-warning">
                        Cancel
                    </a>
                </div>
            @endif
        </div>
    @endif

    <div class="locations-card">
        <table class="locations-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Location Name</th>
                    <th>Description</th>
                    <th>Premises Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $loc)
                    <tr>
                        <td class="text-muted">{{ $loc->location_id }}</td>
                        <td>
                            <i class="bi bi-geo-alt-fill" style="color: #1B5E20; margin-right: 8px;"></i>
                            <span style="font-weight: 600;">{{ $loc->location_name }}</span>
                        </td>
                        <td class="text-muted">{{ $loc->location_description ?? '—' }}</td>
                        <td>
                            <span class="premises-count-badge">
                                <i class="bi bi-building" style="font-size: 11px;"></i>
                                {{ $loc->premises_count ?? 0 }} premises
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="icon-btn edit" data-bs-toggle="modal"
                                    data-bs-target="#editLocationModal{{ $loc->location_id }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.locations.destroy', $loc->location_id) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this location? This will fail if premises exist under it.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="icon-btn del" title="Delete">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editLocationModal{{ $loc->location_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.locations.update', $loc->location_id) }}">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h6 class="modal-title fw-bold">Edit Location</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="modal-label">Location Name</label>
                                            <input type="text" name="location_name" class="modal-input"
                                                value="{{ $loc->location_name }}" required>
                                        </div>
                                        <div class="mb-0">
                                            <label class="modal-label">Description</label>
                                            <input type="text" name="location_description" class="modal-input"
                                                value="{{ $loc->location_description }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="bi bi-geo-alt"></i>
                            <p>No locations found. Click "Add Location" to create one.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($locations->hasPages())
            <div class="pagination-wrapper">
                {{ $locations->links() }}
            </div>
        @endif
    </div>

    {{-- Add Location Modal --}}
    <div class="modal fade" id="addLocationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.locations.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold">Add Location</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="modal-label">Location Name <span class="text-danger">*</span></label>
                            <input type="text" name="location_name" class="modal-input" placeholder="e.g. Tanah Rata"
                                required>
                        </div>
                        <div class="mb-0">
                            <label class="modal-label">Description</label>
                            <input type="text" name="location_description" class="modal-input"
                                placeholder="Optional description">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
