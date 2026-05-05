<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePremisesRequest;
use App\Models\Application;
use App\Models\Location;
use App\Models\Premises;
use App\Services\NotificationService;

class PremisesController extends Controller
{
    public function index()
    {
        $premises = Premises::with('location')->paginate(15);
        $locations = Location::orderBy('location_name')->get();
        $types = ['business_premises', 'market_table', 'market_stall', 'food_stall', 'handicraft', 'workshop', 'various'];
        return view('admin.premises.index', compact('premises', 'locations', 'types'));
    }

    public function store(StorePremisesRequest $request)
    {
        $premises = Premises::create(array_merge($request->validated(), ['created_at' => now()]));

        // Auto-publish vacancy notification if created as available
        if ($premises->premises_status === 'available') {
            $premises->load('location');
            NotificationService::vacancyAnnouncement($premises);
            return back()->with('success', 'Premises added successfully. Vacancy announcement published to all residents.');
        }

        return back()->with('success', 'Premises added successfully.');
    }

    public function update(StorePremisesRequest $request, Premises $premises)
    {
        // Guard: occupied premises status change attempt
        if ($premises->premises_status === 'occupied' && $request->premises_status !== 'occupied') {
            return back()->with('error', "Cannot change the status of \"{$premises->premises_name}\" — " . 'this premises is currently occupied by a resident under an active rental agreement. ' . 'To release this premises, terminate the agreement via Rental Agreements.');
        }

        // Store old values for comparison
        $oldFee = $premises->rental_fee;
        $oldName = $premises->premises_name;
        $oldDescription = $premises->premises_description;
        $oldUnitCount = $premises->unit_count;
        $oldQuota = $premises->applicant_quota;
        $wasAvailable = $premises->premises_status === 'available';
        $wasNotAvailable = $premises->premises_status !== 'available';

        // Only update the fields that are actually editable
        // location_id, premises_type, and applicant_quota are NOT updated here
        $premises->update([
            'premises_name' => $request->premises_name,
            'premises_description' => $request->premises_description,
            'unit_count' => $request->unit_count,
            'rental_fee' => $request->rental_fee,
            'premises_status' => $request->premises_status,
            'updated_at' => now(),
        ]);

        $message = 'Premises updated successfully.';

        // Track changes for available premises (to notify all residents)
        if ($wasAvailable && $premises->premises_status === 'available') {
            $changes = [];

            if ($oldName !== $request->premises_name) {
                $changes[] = 'name from "' . $oldName . '" to "' . $request->premises_name . '"';
            }
            if ((float) $oldFee !== (float) $request->rental_fee) {
                $changes[] = 'rental fee from RM ' . number_format($oldFee, 2) . ' to RM ' . number_format($request->rental_fee, 2);
            }
            if ($oldDescription !== $request->premises_description) {
                $changes[] = 'description updated';
            }
            if ($oldUnitCount !== $request->unit_count) {
                $changes[] = 'unit count from ' . $oldUnitCount . ' to ' . $request->unit_count;
            }
            if ($oldQuota !== $request->applicant_quota) {
                $changes[] = 'applicant quota changed to ' . ucfirst(str_replace('_', ' ', $request->applicant_quota));
            }

            if (!empty($changes)) {
                $premises->load('location');
                NotificationService::vacancyUpdated($premises, $changes);
                $message .= ' A vacancy update announcement has been published to all residents.';
            }
        }

        // Notify current tenant if occupied and fee/name changed
        if ($premises->premises_status === 'occupied') {
            $changes = [];
            if ((float) $oldFee !== (float) $request->rental_fee) {
                $changes[] = 'monthly rental fee from RM ' . number_format($oldFee, 2) . ' to RM ' . number_format($request->rental_fee, 2);
            }
            if ($oldName !== $request->premises_name) {
                $changes[] = 'premises name from "' . $oldName . '" to "' . $request->premises_name . '"';
            }

            if (!empty($changes)) {
                // Find the tenant via active agreement
                $activeApp = Application::with('resident')->where('premises_id', $premises->premises_id)->where('application_status', 'approved')->whereHas('rentalAgreement', fn($q) => $q->where('agreement_status', 'active'))->first();

                if ($activeApp) {
                    NotificationService::premisesUpdated($activeApp->resident_id, $premises->fresh(), $changes);
                }
            }
        }

        // Vacancy prompt if newly set to available
        if ($wasNotAvailable && $request->premises_status === 'available') {
            $message .= ' vacancy_prompt=' . $premises->premises_id;
        }

        return back()->with('success', $message);
    }

    public function destroy(Premises $premises)
    {
        // Block deletion if occupied
        if ($premises->premises_status === 'occupied') {
            return back()->with('error', "Cannot delete \"{$premises->premises_name}\" — " . 'this premises is currently occupied by a resident under an active rental agreement. ' . 'You can only delete a premises when its status is Available or Unavailable.');
        }

        // Check if there are any applications (including soft-deleted)
        $hasApplications = Application::withTrashed()->where('premises_id', $premises->premises_id)->exists();

        if ($hasApplications) {
            return back()->with('error', "Cannot delete \"{$premises->premises_name}\" — " . 'This premises has historical application records. ' . 'For data integrity, only premises with no application history can be deleted.');
        }

        $premises->delete();
        return back()->with('success', 'Premises deleted.');
    }

    // Publish vacancy notification for a specific premises
    public function publishVacancy(Premises $premises)
    {
        if ($premises->premises_status !== 'available') {
            return back()->with('error', 'Vacancy notices can only be published for available premises.');
        }

        $premises->load('location');
        NotificationService::vacancyAnnouncement($premises);

        return back()->with('success', "Vacancy announcement published for \"{$premises->premises_name}\".");
    }
}
