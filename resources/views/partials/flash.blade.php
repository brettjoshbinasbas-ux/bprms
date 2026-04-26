@if (session('success'))
    <div class="flash-success mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="flash-error mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ session('error') }}
    </div>
@endif
@if ($errors->any())
    <div class="flash-error mb-3">
        <strong><i class="bi bi-exclamation-triangle-fill"></i> Please fix the following:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
