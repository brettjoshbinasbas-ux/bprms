@extends('layouts.resident')
@section('title', 'Submit Application')
@section('content')

    <style>
        /* Page */
        .create-page {
            background: #f0f0f0;
            min-height: 100vh;
        }

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #555;
            text-decoration: none;
            margin-bottom: 24px;
            font-weight: 500;
            transition: color 0.15s;
        }

        .back-link:hover {
            color: #1B5E20;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 28px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .page-sub {
            font-size: 14px;
            color: #777;
        }

        /* Two-column grid */
        .two-column-grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 24px;
        }

        /* Form Card */
        .form-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .form-card-header {
            padding: 20px 28px;
            border-bottom: 1px solid #f0f0f0;
            background: #fafafa;
        }

        .form-card-header h5 {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-card-header h5 i {
            color: #1B5E20;
            font-size: 18px;
        }

        .form-card-body {
            padding: 28px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
            display: block;
        }

        .form-label i {
            margin-right: 4px;
            color: #888;
        }

        .form-label .required {
            color: #C62828;
            margin-left: 2px;
        }

        .form-label .optional {
            color: #aaa;
            font-weight: normal;
            font-size: 11px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #1B5E20;
            outline: none;
            box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .input-group {
            display: flex;
            align-items: stretch;
        }

        .input-group-text {
            background: #f5f5f5;
            border: 1.5px solid #e0e0e0;
            border-right: none;
            border-radius: 12px 0 0 12px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #555;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
        }

        .invalid-feedback {
            font-size: 11px;
            color: #dc3545;
            margin-top: 4px;
        }

        /* File Upload Area */
        .file-upload-area {
            border: 2px dashed #e0e0e0;
            border-radius: 12px;
            padding: 16px;
            background: #fafafa;
            transition: all 0.2s;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: #1B5E20;
            background: #f5f9f5;
        }

        .file-upload-area .file-label {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            margin: 0;
        }

        .file-upload-area .file-label i {
            font-size: 24px;
            color: #1B5E20;
        }

        .file-upload-area .file-label span {
            font-size: 13px;
            color: #666;
        }

        .file-upload-area .file-label .file-name {
            font-weight: 500;
            color: #1B5E20;
        }

        .file-input {
            display: none;
        }

        .file-hint {
            font-size: 10px;
            color: #aaa;
            margin-top: 8px;
        }

        /* Divider */
        .section-divider {
            margin: 24px 0;
            border: none;
            border-top: 1px solid #f0f0f0;
        }

        /* Submit buttons */
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .btn-submit {
            background: #1B5E20;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s;
        }

        .btn-submit:hover {
            background: #154d1a;
        }

        .btn-cancel {
            background: #f5f5f5;
            color: #666;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-cancel:hover {
            background: #eee;
            color: #333;
        }

        /* Sidebar Cards */
        .info-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e8e8;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .info-card-header {
            padding: 16px 20px;
            background: #fafafa;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-card-header h6 {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-card-header h6 i {
            color: #1B5E20;
        }

        .info-card-body {
            padding: 20px;
        }

        .info-row {
            margin-bottom: 14px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 11px;
            color: #888;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .info-value.price {
            font-size: 22px;
            font-weight: 800;
            color: #1B5E20;
        }

        .requirements-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .requirements-list li {
            padding: 8px 0;
            font-size: 12px;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #f5f5f5;
        }

        .requirements-list li:last-child {
            border-bottom: none;
        }

        .requirements-list li i {
            color: #1B5E20;
            font-size: 14px;
            width: 18px;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .two-column-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .form-card-body {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-submit,
            .btn-cancel {
                justify-content: center;
            }
        }
    </style>

    <div class="create-page">

        {{-- Back link --}}
        <a href="{{ route('resident.premises.show', $premises->premises_id) }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Premises
        </a>

        {{-- Page Header --}}
        <div class="page-header">
            <div class="page-title">Rental Application Form</div>
            <div class="page-sub">Complete the form below to apply for this premises</div>
        </div>


        <div class="two-column-grid">
            {{-- LEFT: Application Form --}}
            <div class="form-card">
                <div class="form-card-header">
                    <h5>
                        <i class="bi bi-file-earmark-text"></i>
                        Application Details
                    </h5>
                </div>
                <div class="form-card-body">
                    <form method="POST" action="{{ route('resident.applications.store') }}" enctype="multipart/form-data"
                        id="applicationForm">
                        @csrf
                        <input type="hidden" name="premises_id" value="{{ $premises->premises_id }}">

                        {{-- Business Type --}}
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-briefcase"></i> Business Type <span class="required">*</span>
                            </label>
                            <input type="text" name="intended_business_type"
                                class="form-control @error('intended_business_type') is-invalid @enderror"
                                value="{{ old('intended_business_type') }}"
                                placeholder="e.g. Nasi Lemak & Local Cuisine, Handicraft, Retail" required>
                            @error('intended_business_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Financial Position --}}
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-cash-stack"></i> Financial Position (Capital in RM) <span
                                    class="required">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" name="financial_position" step="0.01" min="0"
                                    class="form-control @error('financial_position') is-invalid @enderror"
                                    value="{{ old('financial_position') }}" placeholder="0.00" required>
                            </div>
                            @error('financial_position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="section-divider">

                        {{-- Documents Section --}}
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-paperclip"></i> Supporting Documents
                            </label>

                            {{-- IC Copy --}}
                            <div class="file-upload-area mb-3" onclick="document.getElementById('icCopyInput').click()">
                                <div class="file-label">
                                    <i class="bi bi-card-text"></i>
                                    <span>IC Copy <span class="required">*</span></span>
                                    <span id="icCopyName" class="file-name"></span>
                                </div>
                                <input type="file" id="icCopyInput" name="ic_copy" accept=".pdf,.jpg,.jpeg,.png"
                                    class="file-input" required>
                                <div class="file-hint">PDF, JPG, PNG — max 2MB</div>
                            </div>
                            @error('ic_copy')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            {{-- Applicant Photo --}}
                            <div class="file-upload-area mb-3"
                                onclick="document.getElementById('applicantPhotoInput').click()">
                                <div class="file-label">
                                    <i class="bi bi-camera"></i>
                                    <span>Applicant Photo <span class="required">*</span></span>
                                    <span id="applicantPhotoName" class="file-name"></span>
                                </div>
                                <input type="file" id="applicantPhotoInput" name="applicant_photo"
                                    accept=".jpg,.jpeg,.png" class="file-input" required>
                                <div class="file-hint">JPG, PNG — max 2MB</div>
                            </div>
                            @error('applicant_photo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            {{-- Spouse Photo (Optional) --}}
                            <div class="file-upload-area mb-3"
                                onclick="document.getElementById('spousePhotoInput').click()">
                                <div class="file-label">
                                    <i class="bi bi-person"></i>
                                    <span>Spouse Photo <span class="optional">(Optional)</span></span>
                                    <span id="spousePhotoName" class="file-name"></span>
                                </div>
                                <input type="file" id="spousePhotoInput" name="spouse_photo" accept=".jpg,.jpeg,.png"
                                    class="file-input">
                                <div class="file-hint">JPG, PNG — max 2MB (Required if married)</div>
                            </div>
                            @error('spouse_photo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            {{-- Supporting Document (Optional) --}}
                            <div class="file-upload-area" onclick="document.getElementById('supportingDocInput').click()">
                                <div class="file-label">
                                    <i class="bi bi-files"></i>
                                    <span>Supporting Document <span class="optional">(Optional)</span></span>
                                    <span id="supportingDocName" class="file-name"></span>
                                </div>
                                <input type="file" id="supportingDocInput" name="supporting_document"
                                    accept=".pdf,.jpg,.jpeg,.png" class="file-input">
                                <div class="file-hint">Business Registration Certificate, License, etc. — PDF, JPG, PNG
                                </div>
                            </div>
                            @error('supporting_document')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-send"></i> Submit Application
                            </button>
                            <a href="{{ route('resident.premises.show', $premises->premises_id) }}" class="btn-cancel">
                                <i class="bi bi-x-lg"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RIGHT: Premises Info & Requirements --}}
            <div>
                {{-- Premises Info Card --}}
                <div class="info-card">
                    <div class="info-card-header">
                        <h6>
                            <i class="bi bi-building"></i>
                            Applying For
                        </h6>
                    </div>
                    <div class="info-card-body">
                        <div class="info-row">
                            <div class="info-label">Premises Name</div>
                            <div class="info-value">{{ $premises->premises_name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Type</div>
                            <div class="info-value">{{ $premises->type_label }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Location</div>
                            <div class="info-value">
                                <i class="bi bi-geo-alt" style="font-size:11px;"></i>
                                {{ $premises->location->location_name }}, Cameron Highlands
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Monthly Rental Fee</div>
                            <div class="info-value price">
                                RM {{ number_format($premises->rental_fee, 2) }}
                                <span style="font-size: 12px; font-weight: normal;">/month</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Requirements Card --}}
                <div class="info-card">
                    <div class="info-card-header">
                        <h6>
                            <i class="bi bi-clipboard-check"></i>
                            Application Requirements
                        </h6>
                    </div>
                    <div class="info-card-body">
                        <ul class="requirements-list">
                            <li><i class="bi bi-check-circle-fill"></i> Must be a Malaysian citizen</li>
                            <li><i class="bi bi-check-circle-fill"></i> Priority given to Pahang residents with ≥5 years
                                residency</li>
                            <li><i class="bi bi-check-circle-fill"></i> Must be 18 years or older</li>
                            <li><i class="bi bi-check-circle-fill"></i> Not a civil servant</li>
                            <li><i class="bi bi-check-circle-fill"></i> No criminal record or MDCH offense record</li>
                            <li><i class="bi bi-check-circle-fill"></i> No more than 1 active MDCH business license</li>
                        </ul>
                    </div>
                </div>

                {{-- Tips Card --}}
                <div class="info-card">
                    <div class="info-card-header">
                        <h6>
                            <i class="bi bi-lightbulb"></i>
                            Application Tips
                        </h6>
                    </div>
                    <div class="info-card-body">
                        <ul class="requirements-list">
                            <li><i class="bi bi-envelope-paper"></i> Ensure all documents are clear and readable</li>
                            <li><i class="bi bi-check-circle"></i> Double-check your business type and financial position
                            </li>
                            <li><i class="bi bi-clock-history"></i> Applications are reviewed within 7-14 working days</li>
                            <li><i class="bi bi-credit-card"></i> If approved, payment is required to secure the premises
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // File upload display helpers
        function setupFileInput(inputId, displaySpanId) {
            const input = document.getElementById(inputId);
            const span = document.getElementById(displaySpanId);
            if (input && span) {
                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        span.textContent = '✓ ' + this.files[0].name;
                        span.style.color = '#1B5E20';
                    } else {
                        span.textContent = '';
                    }
                });
            }
        }

        setupFileInput('icCopyInput', 'icCopyName');
        setupFileInput('applicantPhotoInput', 'applicantPhotoName');
        setupFileInput('spousePhotoInput', 'spousePhotoName');
        setupFileInput('supportingDocInput', 'supportingDocName');
    </script>

@endsection
