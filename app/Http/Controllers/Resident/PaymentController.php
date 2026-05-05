<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\StorePaymentRequest;
use App\Models\Application;
use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // Show payment form for an approved application
    public function showPayment($applicationId)
    {
        $resident = auth('resident')->user();
        $application = Application::with(['premises.location'])
            ->where('application_id', $applicationId)
            ->where('resident_id', $resident->resident_id)
            ->where('application_status', 'approved')
            ->whereDoesntHave('payment')
            ->firstOrFail();

        // Save the current rental fee in the session in case there is fee changes before payment.
        session(['payment.original_fee' => $application->premises->rental_fee]);

        return view('resident.payment.form', compact('application'));
    }

    // Process payment — inserts payment row; trigger creates rental_agreement
    public function processPayment(StorePaymentRequest $request)
    {
        $resident = auth('resident')->user();
        $application = Application::with('premises')->where('application_id', $request->application_id)->where('resident_id', $resident->resident_id)->where('application_status', 'approved')->whereDoesntHave('payment')->firstOrFail();

        // CHECK: Has the rental fee changed since approval?
        $originalFee = session('payment.original_fee');
        $currentFee = $application->premises->rental_fee;
        $feeChanged = ($originalFee) && ($originalFee != $currentFee);

        if ($feeChanged) {
            // Clear the session variable
            session()->forget('payment.original_fee');

            // Show warning to resident with the fee difference
            return back()->with('error', 'The rental fee for this premises has been updated from RM ' . number_format($originalFee, 2) . ' to RM ' . number_format($currentFee, 2) . '. ' . 'Please review the new fee before proceeding with payment. ' . 'If you wish to proceed, please refresh the page and make payment with the updated amount.');
        }

        // Clear session variable
        session()->forget('payment.original_fee');

        $expiryDate = "{$request->expiry_year}-" . str_pad($request->expiry_month, 2, '0', STR_PAD_LEFT) . '-01';

        DB::beginTransaction();
        try {
            // Insert payment with status 'pending' first
            $payment = Payment::create([
                'application_id' => $application->application_id,
                'amount' => $application->premises->rental_fee,
                'card_number' => preg_replace('/\s+/', '', $request->card_number),
                'card_expiry_date' => $expiryDate,
                'payment_date' => now(),
                'payment_status' => 'pending',
                'created_at' => now(),
            ]);

            // Update to 'completed' — this fires trg_after_payment_completed
            // which auto-inserts rental_agreement and triggers premises status update
            $payment->update(['payment_status' => 'completed']);

            // ── AFTER payment completes, premises becomes occupied ──
            // Reject ALL other applications for this premises that are:
            //   1. Pending, OR
            //   2. Approved but WITHOUT an active rental agreement (awaiting payment)
            $otherApps = Application::with('premises')
                ->where('premises_id', $application->premises_id)
                ->where('resident_id', '!=', $resident->resident_id)
                ->where(function ($q) {
                    $q->where('application_status', 'pending')->orWhere(function ($q2) {
                        $q2->where('application_status', 'approved')->whereDoesntHave('rentalAgreement');
                    });
                })
                ->get();

            $rejectedCount = 0;
            foreach ($otherApps as $otherApp) {
                $otherApp->update([
                    'application_status' => 'rejected',
                    'remarks' => 'This premises has been rented to another applicant. The premises "' . $application->premises->premises_name . '" is no longer available.',
                    'reviewed_at' => now(),
                    'updated_at' => now(),
                ]);

                NotificationService::applicationRejected($otherApp, 'This premises has been rented to another applicant. The premises "' . $application->premises->premises_name . '" is no longer available.');
                $rejectedCount++;
            }

            DB::commit();

            $successMessage = 'Payment processed successfully.';
            if ($rejectedCount > 0) {
                $successMessage .= ' ' . $rejectedCount . ' other application(s) for this premises have been automatically rejected.';
            }

            return redirect()->route('resident.payment.confirm', $payment->payment_id)->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    // Confirmation page
    public function confirm($paymentId)
    {
        $resident = auth('resident')->user();
        $payment = Payment::with(['application.premises.location', 'rentalAgreement'])
            ->where('payment_id', $paymentId)
            ->whereHas('application', fn($q) => $q->where('resident_id', $resident->resident_id))
            ->firstOrFail();

        return view('resident.payment.confirm', compact('payment'));
    }
}
