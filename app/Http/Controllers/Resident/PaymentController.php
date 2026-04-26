<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\StorePaymentRequest;
use App\Models\Application;
use App\Models\Payment;
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

        return view('resident.payment.form', compact('application'));
    }

    // Process payment — inserts payment row; trigger creates rental_agreement
    public function processPayment(StorePaymentRequest $request)
    {
        $resident = auth('resident')->user();
        $application = Application::with('premises')->where('application_id', $request->application_id)->where('resident_id', $resident->resident_id)->where('application_status', 'approved')->whereDoesntHave('payment')->firstOrFail();

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

            DB::commit();

            return redirect()->route('resident.payment.confirm', $payment->payment_id)->with('success', 'Payment processed successfully.');
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
