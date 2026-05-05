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

        /* Vacancy Prompt Banner */
        .vacancy-banner {
            background: #E8F5E9;
            border: 1px solid #4CAF50;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .vacancy-banner i {
            color: #2E7D32;
            font-size: 20px;
        }

        .btn-publish {
            background: #1B5E20;
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

        .btn-publish:hover {
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

        .premises-table thead th:last-child {
            text-align: center;
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

        .premises-table tbody td:last-child {
            text-align: center;
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

        .quota-badge {
            background: #E8F5E9;
            color: #2E7D32;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
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
            justify-content: center;
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

        /* Tenant Info Box */
        .tenant-info-box {
            background: #FFF8E1;
            border: 1px solid #FFA726;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .tenant-info-box i {
            color: #F57F17;
        }

        .warning-box {
            background: #FFEBEE;
            border: 1px solid #EF5350;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 12px;
            display: flex;
            gap: 10px;
        }

        .warning-box i {
            color: #C62828;
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

        /* Disabled select styling */
        select.modal-select:disabled {
            background-color: #f5f5f5;
            color: #666;
            cursor: not-allowed;
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

    {{-- Vacancy prompt banner (injected into success message) --}}
    @if (session('success') && str_contains(session('success'), 'vacancy_prompt='))
        @php
            preg_match('/vacancy_prompt=(\d+)/', session('success'), $m);
            $vpId = $m[1] ?? null;
            $vpPremises = $vpId ? \App\Models\Premises::find($vpId) : null;
        @endphp
        @if ($vpPremises)
            <div class="vacancy-banner">
                <div>
                    <i class="bi bi-megaphone-fill me-2"></i>
                    <strong>{{ $vpPremises->premises_name }}</strong> is now available.
                    Would you like to publish a vacancy announcement to all residents?
                </div>
                <form method="POST" action="{{ url('/admin/premises/' . $vpPremises->premises_id . '/publish-vacancy') }}">
                    @csrf
                    <button type="submit" class="btn-publish">
                        <i class="bi bi-megaphone me-1"></i>Publish Vacancy Notice
                    </button>
                </form>
            </div>
        @endif
    @endif

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
                                    {{ Str::limit($p->premises_description, 45) }}
                                </div>
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
                        <td class="td-status">
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
                        <td class="td-actions">
                            <div class="actions-cell">
                                @if ($p->premises_status === 'occupied')
                                    <button class="icon-btn edit" data-bs-toggle="modal"
                                        data-bs-target="#editOccupiedModal{{ $p->premises_id }}"
                                        title="Edit Occupied Premises">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                @else
                                    <button class="icon-btn edit" data-bs-toggle="modal"
                                        data-bs-target="#editPremisesModal{{ $p->premises_id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    {{-- Delete button only shown for non-occupied premises --}}
                                    <form method="POST" action="{{ route('admin.premises.destroy', $p->premises_id) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this premises? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="icon-btn del">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- ── Edit Modal (non-occupied) ──────────────────── --}}
                    @if ($p->premises_status !== 'occupied')
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
                                                    <select name="location_id" class="modal-select" disabled>
                                                        @foreach ($locations as $loc)
                                                            <option value="{{ $loc->location_id }}"
                                                                {{ $p->location_id == $loc->location_id ? 'selected' : '' }}>
                                                                {{ $loc->location_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="location_id" value="{{ $p->location_id }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="modal-label">Type</label>
                                                    <select name="premises_type" class="modal-select" disabled>
                                                        @foreach ($types as $type)
                                                            <option value="{{ $type }}"
                                                                {{ $p->premises_type === $type ? 'selected' : '' }}>
                                                                {{ ucwords(str_replace('_', ' ', $type)) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="premises_type"
                                                        value="{{ $p->premises_type }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="modal-label">Rental Fee (RM)</label>
                                                    <input type="number" name="rental_fee" step="0.01" min="0"
                                                        class="modal-input" value="{{ $p->rental_fee }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="modal-label">Status</label>
                                                    <select name="premises_status" class="modal-select" required>
                                                        @foreach (['available', 'unavailable'] as $s)
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
                    @endif

                    {{-- ── Edit Modal (OCCUPIED — shows tenant, locks status) ── --}}
                    @if ($p->premises_status === 'occupied')
                        @php
                            // Load active tenant for this premises
                            $activeApp = \App\Models\Application::with(['resident', 'rentalAgreement'])
                                ->where('premises_id', $p->premises_id)
                                ->where('application_status', 'approved')
                                ->whereHas('rentalAgreement', fn($q) => $q->where('agreement_status', 'active'))
                                ->first();
                        @endphp
                        <div class="modal fade" id="editOccupiedModal{{ $p->premises_id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.premises.update', $p->premises_id) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h6 class="modal-title fw-bold">
                                                Edit Premises
                                                <span class="status-badge occupied ms-2"
                                                    style="font-size: 11px;">Occupied</span>
                                            </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">

                                            {{-- Current Tenant Info --}}
                                            @if ($activeApp)
                                                <div class="tenant-info-box">
                                                    <div class="d-flex gap-3 align-items-start">
                                                        <i class="bi bi-person-fill mt-1" style="font-size: 18px;"></i>
                                                        <div>
                                                            <div class="fw-semibold" style="font-size: 13px;">Current
                                                                Tenant</div>
                                                            <div style="font-size: 13px;">
                                                                {{ $activeApp->resident->full_name }}
                                                                — IC: {{ $activeApp->resident->resident_ic_number }}
                                                                — {{ $activeApp->resident->resident_phone }}
                                                            </div>
                                                            <div class="text-muted" style="font-size: 12px;">
                                                                Agreement #{{ $activeApp->rentalAgreement->agreement_id }}
                                                                — Active until
                                                                {{ $activeApp->rentalAgreement->agreement_end_date->format('d M Y') }}
                                                            </div>
                                                            <a href="{{ route('admin.agreements.show', $activeApp->rentalAgreement->agreement_id) }}"
                                                                class="text-warning fw-semibold text-decoration-none"
                                                                style="font-size: 12px;">
                                                                View Agreement <i class="bi bi-arrow-right ms-1"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Notice: status is locked --}}
                                            <div class="warning-box">
                                                <i class="bi bi-lock-fill"></i>
                                                <div>
                                                    <strong>Status is locked.</strong> This premises is currently
                                                    occupied.
                                                    To change the status, terminate the rental agreement via
                                                    <a href="{{ route('admin.agreements.index') }}"
                                                        class="fw-semibold">Rental Agreements</a>.
                                                    Changes to name or rental fee will notify the current tenant.
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <label class="modal-label">Premises Name</label>
                                                    <input type="text" name="premises_name" class="modal-input"
                                                        value="{{ $p->premises_name }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="modal-label">Location</label>
                                                    <select name="location_id" class="modal-select" disabled>
                                                        @foreach ($locations as $loc)
                                                            <option value="{{ $loc->location_id }}"
                                                                {{ $p->location_id == $loc->location_id ? 'selected' : '' }}>
                                                                {{ $loc->location_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="location_id"
                                                        value="{{ $p->location_id }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="modal-label">Type</label>
                                                    <select name="premises_type" class="modal-select" disabled>
                                                        @foreach ($types as $type)
                                                            <option value="{{ $type }}"
                                                                {{ $p->premises_type === $type ? 'selected' : '' }}>
                                                                {{ ucwords(str_replace('_', ' ', $type)) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="premises_type"
                                                        value="{{ $p->premises_type }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="modal-label">Rental Fee (RM)</label>
                                                    <input type="number" name="rental_fee" step="0.01"
                                                        min="0" class="modal-input" value="{{ $p->rental_fee }}"
                                                        required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="modal-label">Status</label>
                                                    <input type="text" class="form-control" value="Occupied" disabled
                                                        style="background: #f5f5f5;">
                                                    <input type="hidden" name="premises_status" value="occupied">
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
                                            <button type="submit" class="btn btn-primary"
                                                onclick="return confirm('Save changes? Any fee or name changes will notify the current tenant.')">
                                                Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

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
                                    placeholder="e.g. No. 6 Food Stall Jalan Besar">
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
                            <div class="col-md-6">
                                <label class="modal-label">Initial Status <span class="text-danger">*</span></label>
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
