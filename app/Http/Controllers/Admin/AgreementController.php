<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalAgreement;
use App\Services\NotificationService;
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
    // Modified: adds vacancy_prompt to success message for publishing vacancy notice
    public function terminate($id)
    {
        $agreement = RentalAgreement::with(['application.premises', 'application.resident'])
            ->where('agreement_id', $id)
            ->where('agreement_status', 'active')
            ->firstOrFail();

        $premisesId = $agreement->application->premises_id;
        $premisesName = $agreement->application->premises->premises_name ?? 'the premises';
        $residentId = $agreement->application->resident_id;

        // Send notification to the resident BEFORE termination
        NotificationService::agreementTerminated(
            $residentId,
            $premisesName,
            $agreement->agreement_id,
            null, // optional remarks
        );

        // Fires trg_after_agreement_status_update → premises back to 'available'
        $agreement->update([
            'agreement_status' => 'terminated',
            'updated_at' => now(),
        ]);

        return back()->with('success', "Agreement terminated. \"{$premisesName}\" is now available. " . "vacancy_prompt={$premisesId}");
    }
}
