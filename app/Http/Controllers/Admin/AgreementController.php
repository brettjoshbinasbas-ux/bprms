<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalAgreement;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    public function index(Request $request)
    {
        $query = RentalAgreement::with(['application.resident', 'application.premises.location', 'payment']);

        if ($request->filled('status')) {
            $query->where('agreement_status', $request->status);
        }

        $agreements = $query->orderByDesc('signed_at')->paginate(15);
        return view('admin.agreements.index', compact('agreements'));
    }

    public function show($id)
    {
        $agreement = RentalAgreement::with(['application.resident', 'application.premises.location', 'payment'])->findOrFail($id);

        return view('admin.agreements.show', compact('agreement'));
    }

    // Admin terminates an active agreement
    public function terminate($id)
    {
        $agreement = RentalAgreement::where('agreement_id', $id)->where('agreement_status', 'active')->firstOrFail();

        // This fires trg_after_agreement_status_update → premises back to 'available'
        $agreement->update([
            'agreement_status' => 'terminated',
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Agreement terminated. Premises is now available.');
    }
}
