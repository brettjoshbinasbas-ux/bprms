<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLocationRequest;
use App\Models\Application;
use App\Models\Location;
use App\Models\Premises;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::withCount('premises')->orderBy('location_name')->paginate(15);
        return view('admin.locations.index', compact('locations'));
    }

    public function store(StoreLocationRequest $request)
    {
        Location::create($request->validated());
        return back()->with('success', 'Location added successfully.');
    }

    public function update(StoreLocationRequest $request, Location $location)
    {
        $location->update($request->validated());
        return back()->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $premises = $location->premises()->get();

        // Case 1: No premises at all — safe to delete
        if ($premises->isEmpty()) {
            $location->delete();
            return back()->with('success', 'Location deleted.');
        }

        // Case 2: At least one occupied premises — hard block
        $occupiedCount = $premises->where('premises_status', 'occupied')->count();
        if ($occupiedCount > 0) {
            return back()->with('error', "Cannot delete \"{$location->location_name}\": {$occupiedCount} premises in this location " . ($occupiedCount === 1 ? 'is' : 'are') . ' currently occupied.');
        }

        // ── Check for premises with application history ───────────
        $premisesWithHistory = [];
        $deletablePremises = [];
        
        foreach ($premises as $premise) {
            $hasApplications = Application::withTrashed()
                ->where('premises_id', $premise->premises_id)
                ->exists();
            
            if ($hasApplications) {
                $premisesWithHistory[] = $premise->premises_name;
            } else {
                $deletablePremises[] = $premise;
            }
        }

        $hasHistory = !empty($premisesWithHistory);
        $historyCount = count($premisesWithHistory);
        $deletableCount = count($deletablePremises);

        // Case 3: Has premises but none occupied
        if (!request()->boolean('confirm')) {
            $count = $premises->count();
            
            $message = "Location \"{$location->location_name}\" has {$count} " . ($count === 1 ? 'premises' : 'premises') . ' attached. ';

            if ($hasHistory) {
                $message .= "\n⚠️ IMPORTANT: {$historyCount} of these premises have rental application history (including past applications and agreements). ";
                $message .= "These premises CANNOT be deleted because they have existing application records.\n\n";
                $message .= "Premises that will REMAIN: " . implode(', ', $premisesWithHistory) . ".\n\n";
                $message .= "Only {$deletableCount} premises without history can be deleted.\n\n";
            }

            $message .= 'Force-deleting will remove ONLY deletable premises. ' . 
                        'To remove specific premises first, manage them under Premises. ' . 
                        "location_force_delete_id={$location->location_id}";

            return back()->with('warning', $message);
        }

        // ── Force delete — delete ONLY premises WITHOUT application history ──
        $deletedCount = 0;
        $skippedNames = [];

        foreach ($premises as $premise) {
            $hasApplications = Application::withTrashed()
                ->where('premises_id', $premise->premises_id)
                ->exists();
            
            if (!$hasApplications) {
                $premise->delete();
                $deletedCount++;
            } else {
                $skippedNames[] = $premise->premises_name;
            }
        }

        // Check if any premises remain
        $remainingPremises = $location->premises()->count();
        
        if ($remainingPremises > 0) {
            // Location cannot be deleted because premises still exist
            return back()->with('error', 
                "Location \"{$location->location_name}\" was NOT deleted because {$remainingPremises} premises still exist " .
                "(they have application history and cannot be deleted). " .
                "Deleted {$deletedCount} premises. " .
                "Remaining premises: " . implode(', ', $skippedNames) . ". " .
                "You cannot delete this location while these premises exist. " .
                "You must manually archive or handle these premises' application history first."
            );
        } else {
            // All premises were deleted, safe to delete location
            $location->delete();
            return back()->with('success', 
                "Location deleted successfully. {$deletedCount} premises removed."
            );
        }
    }
}