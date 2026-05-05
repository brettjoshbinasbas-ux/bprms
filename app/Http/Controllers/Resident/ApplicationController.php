<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\StoreApplicationRequest;
use App\Models\Application;
use App\Models\Document;
use App\Models\Premises;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    // List the authenticated resident's applications
    public function index(Request $request)
    {
        $resident = auth('resident')->user();

        $query = Application::with(['premises.location', 'rentalAgreement'])->where('resident_id', $resident->resident_id);

        if ($request->filled('status')) {
            $status = $request->status;

            switch ($status) {
                case 'active':
                    // Approved applications with ACTIVE rental agreement
                    $query->where('application_status', 'approved')->whereHas('rentalAgreement', fn($q) => $q->where('agreement_status', 'active'));
                    break;

                case 'terminated':
                    // Approved applications with TERMINATED rental agreement
                    $query->where('application_status', 'approved')->whereHas('rentalAgreement', fn($q) => $q->where('agreement_status', 'terminated'));
                    break;

                case 'expired':
                    // Approved applications with EXPIRED rental agreement
                    $query->where('application_status', 'approved')->whereHas('rentalAgreement', fn($q) => $q->where('agreement_status', 'expired'));
                    break;

                case 'approved':
                    // Approved applications WITHOUT any rental agreement (awaiting payment)
                    $query->where('application_status', 'approved')->whereDoesntHave('rentalAgreement');
                    break;

                case 'pending':
                case 'rejected':
                case 'cancelled':
                    // Direct status match
                    $query->where('application_status', $status);
                    break;

                default:
                    // Fallback for any other value
                    $query->where('application_status', $status);
                    break;
            }
        }

        $applications = $query->orderByDesc('application_date')->paginate(10);

        return view('resident.applications.index', compact('applications'));
    }

    // Show form to apply for a specific premises
    public function create(Request $request)
    {
        $request->validate(['premises_id' => 'required|exists:premises,premises_id']);

        $premises = Premises::with('location')->where('premises_id', $request->premises_id)->where('premises_status', 'available')->firstOrFail();

        return view('resident.applications.create', compact('premises'));
    }

    // Store new application with documents
    public function store(StoreApplicationRequest $request)
    {
        $resident = auth('resident')->user();

        // Check premises is still available
        $premises = Premises::where('premises_id', $request->premises_id)->where('premises_status', 'available')->firstOrFail();

        // Check if resident already has an ACTIVE rental agreement (ANY premises)
        // MDCH policy: "Tidak mempunyai lebih daripada 1 lesen perniagaan dengan MDCH"
        $hasActiveAgreement = Application::where('resident_id', $resident->resident_id)->where('application_status', 'approved')->whereHas('rentalAgreement', fn($q) => $q->where('agreement_status', 'active'))->exists();

        if ($hasActiveAgreement) {
            return back()->with('error', 'You already have an active rental agreement for another premises. ' . 'MDCH policy allows only one active business license at a time. ' . 'You cannot submit a new application while you have an active agreement.');
        }

        // Check for PENDING application for THIS premises
        $hasPendingForThisPremises = Application::where('resident_id', $resident->resident_id)->where('premises_id', $premises->premises_id)->where('application_status', 'pending')->exists();

        if ($hasPendingForThisPremises) {
            return back()->with('error', 'You already have a pending application for this premises. ' . 'Please wait for the MDCH committee to review your existing application. ' . 'Duplicate applications for the same premises are not allowed. ' . 'You can check the status of your application in "My Applications".');
        }

        // Check for APPROVED application with ACTIVE agreement for THIS premises
        $hasActiveForThisPremises = Application::where('resident_id', $resident->resident_id)->where('premises_id', $premises->premises_id)->where('application_status', 'approved')->whereHas('rentalAgreement', fn($q) => $q->where('agreement_status', 'active'))->exists();

        if ($hasActiveForThisPremises) {
            return back()->with('error', 'You already have an active rental agreement for this premises. ' . 'You cannot apply again while you have an active agreement. ' . 'If your agreement has been terminated, you may apply again.');
        }

        // Check for APPROVED application WITHOUT active agreement (awaiting payment)
        $hasApprovedWithoutPayment = Application::where('resident_id', $resident->resident_id)->where('premises_id', $premises->premises_id)->where('application_status', 'approved')->whereDoesntHave('rentalAgreement')->exists();

        if ($hasApprovedWithoutPayment) {
            return back()->with('error', 'You already have an approved application for this premises awaiting payment. ' . 'Please proceed to payment in "My Applications" to complete your rental agreement. ' . 'You cannot submit another application for the same premises.');
        }

        DB::beginTransaction();
        try {
            $application = Application::create([
                'resident_id' => $resident->resident_id,
                'premises_id' => $premises->premises_id,
                'intended_business_type' => $request->intended_business_type,
                'financial_position' => $request->financial_position,
                'application_status' => 'pending',
                'application_date' => now(),
                'created_at' => now(),
            ]);

            // Handle document uploads
            $docTypes = ['ic_copy', 'applicant_photo', 'spouse_photo', 'supporting_document'];
            foreach ($docTypes as $type) {
                if ($request->hasFile($type)) {
                    $file = $request->file($type);
                    $filename = $type . '_' . $application->application_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('documents/' . $application->application_id, $filename, 'public');

                    Document::create([
                        'application_id' => $application->application_id,
                        'document_type' => $type,
                        'document_filename' => $filename,
                        'document_path' => $path,
                        'uploaded_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('resident.applications.show', $application->application_id)->with('success', 'Application submitted successfully. Please await review.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Submission failed: ' . $e->getMessage());
        }
    }

    // View a specific application
    public function show($id)
    {
        $resident = auth('resident')->user();
        $application = Application::with(['premises.location', 'documents', 'payment', 'rentalAgreement', 'reviewer'])
            ->where('application_id', $id)
            ->where('resident_id', $resident->resident_id)
            ->firstOrFail();

        return view('resident.applications.show', compact('application'));
    }

    // Cancel a pending application
    public function cancel($id)
    {
        $resident = auth('resident')->user();
        $application = Application::where('application_id', $id)->where('resident_id', $resident->resident_id)->where('application_status', 'pending')->firstOrFail();

        $application->update([
            'application_status' => 'cancelled',
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Application cancelled.');
    }
}
