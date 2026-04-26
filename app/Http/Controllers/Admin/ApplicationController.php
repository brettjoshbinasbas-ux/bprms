<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewApplicationRequest;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['resident', 'premises.location', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('application_status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas(
                'resident',
                fn($q) => $q
                    ->where('resident_first_name', 'like', "%{$search}%")
                    ->orWhere('resident_last_name', 'like', "%{$search}%")
                    ->orWhere('resident_ic_number', 'like', "%{$search}%")
                    ->orWhere('resident_email', 'like', "%{$search}%"),
            );
        }

        $applications = $query->orderByDesc('application_date')->paginate(15);
        return view('admin.applications.index', compact('applications'));
    }

    public function show($id)
    {
        $application = Application::with(['resident', 'premises.location', 'documents', 'payment', 'rentalAgreement', 'reviewer'])->findOrFail($id);

        return view('admin.applications.show', compact('application'));
    }

    // Admin approves or rejects
    public function review(ReviewApplicationRequest $request, $id)
    {
        $admin = auth('admin')->user();
        $application = Application::where('application_id', $id)->where('application_status', 'pending')->firstOrFail();

        $application->update([
            'application_status' => $request->decision,
            'reviewed_by' => $admin->admin_id,
            'reviewed_at' => now(),
            'remarks' => $request->remarks,
            'updated_at' => now(),
        ]);

        $msg = $request->decision === 'approved' ? 'Application approved. Resident may now proceed to payment.' : 'Application rejected.';

        return back()->with('success', $msg);
    }
}
