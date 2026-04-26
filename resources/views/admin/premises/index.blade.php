@extends('layouts.admin')
@section('title', 'Manage Premises')
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

        /* Table Card */
        .premises-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .premises-table {
            width: 100%;
            border-collapse: collapse;
        }

        .premises-table thead tr {
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
        }

        .premises-table thead th {
            text-align: left;
            padding: 16px 20px;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .premises-table tbody tr {
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s;
        }

        .premises-table tbody tr:hover {
            background: #fafafa;
        }

        .premises-table tbody tr:last-child {
            border-bottom: none;
        }

        .premises-table tbody td {
            padding: 16px 20px;
            font-size: 14px;
            vertical-align: middle;
        }

        /* Type Badge */
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

        /* Rental Fee */
        .rental-fee {
            font-weight: 700;
            color: #1B5E20;
            white-space: nowrap;
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

        .modal-input,
        .modal-select,
        .modal-textarea {
            width: 100%;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.2s;
            margin-bottom: 16px;
        }

        .modal-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .modal-input:focus,
        .modal-select:focus,
        .modal-textarea:focus {
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

        @media (max-width: 900px) {

            .premises-table thead th,
            .premises-table tbody td {
                padding: 12px 12px;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <div class="page-title">Manage Premises</div>
            <div class="page-sub">Add, edit, or remove MDCH-managed premises.</div>
        </div>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addPremisesModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Add Premises
        </button>
    </div>

    @include('partials.flash')

    <div class="premises-card">
        <table class="premises-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Premises Name</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Rental Fee</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($premises as $p)
                    <tr>
                        <td class="text-muted">{{ $p->premises_id }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ $p->premises_name }}</div>
                            @if ($p->premises_description)
                                <div style="font-size: 12px;" class="text-muted">
                                    {{ Str::limit($p->premises_description, 50) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="type-badge">{{ $p->type_label }}</span>
                        </td>
                        <td class="text-muted">
                            <i class="bi bi-geo-alt" style="font-size: 12px;"></i>
                            {{ $p->location->location_name ?? '—' }}
                        </td>
                        <td class="rental-fee">RM {{ number_format($p->rental_fee, 2) }}</td>
                        <td>
                            @php
                                $statusClass = match ($p->premises_status) {
                                    'available' => 'available',
                                    'occupied' => 'occupied',
                                    'unavailable' => 'unavailable',
                                    default => 'available',
                                };
                                $statusIcon = match ($p->premises_status) {
                                    'available' => 'bi-check-circle-fill',
                                    'occupied' => 'bi-building-fill',
                                    'unavailable' => 'bi-x-circle-fill',
                                    default => 'bi-circle',
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                <i class="{{ $statusIcon }}" style="font-size: 10px;"></i>
                                {{ ucfirst($p->premises_status) }}
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button class="icon-btn edit" data-bs-toggle="modal"
                                    data-bs-target="#editPremisesModal{{ $p->premises_id }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.premises.destroy', $p->premises_id) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this premises? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="icon-btn del" title="Delete">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Premises Modal --}}
                    <div class="modal fade" id="editPremisesModal{{ $p->premises_id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.premises.update', $p->premises_id) }}">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h6 class="modal-title fw-bold">Edit Premises</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label class="modal-label">Premises Name</label>
                                                <input type="text" name="premises_name" class="modal-input"
                                                    value="{{ $p->premises_name }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="modal-label">Location</label>
                                                <select name="location_id" class="modal-select" required>
                                                    @foreach ($locations as $loc)
                                                        <option value="{{ $loc->location_id }}"
                                                            {{ $p->location_id == $loc->location_id ? 'selected' : '' }}>
                                                            {{ $loc->location_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="modal-label">Type</label>
                                                <select name="premises_type" class="modal-select" required>
                                                    @foreach ($types as $type)
                                                        <option value="{{ $type }}"
                                                            {{ $p->premises_type === $type ? 'selected' : '' }}>
                                                            {{ ucwords(str_replace('_', ' ', $type)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="modal-label">Rental Fee (RM)</label>
                                                <input type="number" name="rental_fee" step="0.01" min="0"
                                                    class="modal-input" value="{{ $p->rental_fee }}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="modal-label">Status</label>
                                                <select name="premises_status" class="modal-select" required>
                                                    @foreach (['available', 'occupied', 'unavailable'] as $s)
                                                        <option value="{{ $s }}"
                                                            {{ $p->premises_status === $s ? 'selected' : '' }}>
                                                            {{ ucfirst($s) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="modal-label">Description</label>
                                                <input type="text" name="premises_description" class="modal-input"
                                                    value="{{ $p->premises_description }}">
                                            </div>
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
                        <td colspan="7" class="empty-state">
                            <i class="bi bi-building-x"></i>
                            <p>No premises found. Click "Add Premises" to create one.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($premises->hasPages())
            <div class="pagination-wrapper">
                {{ $premises->links() }}
            </div>
        @endif
    </div>

    {{-- Add Premises Modal --}}
    <div class="modal fade" id="addPremisesModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.premises.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold">Add Premises</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="modal-label">Premises Name <span class="text-danger">*</span></label>
                                <input type="text" name="premises_name" class="modal-input" required
                                    placeholder="e.g. Lot 5 Tanah Rata Market">
                            </div>
                            <div class="col-md-4">
                                <label class="modal-label">Location <span class="text-danger">*</span></label>
                                <select name="location_id" class="modal-select" required>
                                    <option value="">— Select —</option>
                                    @foreach ($locations as $loc)
                                        <option value="{{ $loc->location_id }}">{{ $loc->location_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="modal-label">Type <span class="text-danger">*</span></label>
                                <select name="premises_type" class="modal-select" required>
                                    <option value="">— Select —</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="modal-label">Rental Fee (RM) <span class="text-danger">*</span></label>
                                <input type="number" name="rental_fee" step="0.01" min="0"
                                    class="modal-input" required placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <label class="modal-label">Status <span class="text-danger">*</span></label>
                                <select name="premises_status" class="modal-select" required>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="modal-label">Description</label>
                                <input type="text" name="premises_description" class="modal-input"
                                    placeholder="Optional description">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Premises</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
