<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident::withCount('applications');

        // Handle showing trashed (deactivated) residents
        if ($request->filled('status')) {
            if ($request->status === 'deactivated') {
                $query->onlyTrashed();
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('resident_first_name', 'like', "%{$search}%")
                    ->orWhere('resident_last_name', 'like', "%{$search}%")
                    ->orWhere('resident_ic_number', 'like', "%{$search}%")
                    ->orWhere('resident_email', 'like', "%{$search}%");
            });
        }

        $residents = $query->orderBy('resident_last_name')->paginate(15);
        return view('admin.residents.index', compact('residents'));
    }

    public function show($id)
    {
        // Allow viewing soft-deleted residents
        $resident = Resident::withTrashed()
            ->with([
                'applications' => function ($q) {
                    $q->withTrashed(); // Include soft-deleted applications
                    $q->with(['premises.location']);
                },
            ])
            ->findOrFail($id);

        return view('admin.residents.show', compact('resident'));
    }

    // Deactivate a resident (soft delete)
    public function deactivate($id)
    {
        $resident = Resident::findOrFail($id);

        // Check if resident has active agreement before deactivating
        if ($resident->hasActiveAgreement()) {
            return back()->with('error', 'Cannot deactivate a resident with an active rental agreement. Please terminate the agreement first.');
        }

        $resident->delete(); // This triggers cascading soft delete of applications

        return back()->with('success', "Resident \"{$resident->full_name}\" has been deactivated. All associated applications have been archived.");
    }

    // Restore a deactivated resident
    public function restore($id)
    {
        $resident = Resident::withTrashed()->findOrFail($id);
        $resident->restore(); // This triggers cascading restore of applications

        return back()->with('success', "Resident \"{$resident->full_name}\" has been restored. All associated applications have been restored.");
    }
}
