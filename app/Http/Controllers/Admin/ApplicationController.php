<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewApplicationRequest;
use App\Models\Application;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        // Use the database view vw_application_details
        $query = DB::table('vw_application_details');

        // Handle archived filtering (using deleted_at from the view)
        if ($request->filled('show_archived') && $request->show_archived === 'yes') {
            // Include soft-deleted applications - no filter needed, view has all records
        } else {
            // Exclude soft-deleted applications
            $query->whereNull('deleted_at');
        }

        // Status filtering
        if ($request->filled('status')) {
            $status = $request->status;

            switch ($status) {
                case 'active':
                    $query->where('application_status', 'approved')->where('agreement_status', 'active');
                    break;

                case 'terminated':
                    $query->where('application_status', 'approved')->where('agreement_status', 'terminated');
                    break;

                case 'expired':
                    $query->where('application_status', 'approved')->where('agreement_status', 'expired');
                    break;

                case 'approved':
                    $query->where('application_status', 'approved')->whereNull('agreement_id');
                    break;

                case 'pending':
                case 'rejected':
                case 'cancelled':
                    $query->where('application_status', $status);
                    break;

                default:
                    $query->where('application_status', $status);
                    break;
            }
        }

        // Search by resident name, IC, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('resident_full_name', 'like', "%{$search}%")
                    ->orWhere('resident_ic_number', 'like', "%{$search}%")
                    ->orWhere('resident_email', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderByDesc('application_date')->paginate(15);

        return view('admin.applications.index', compact('applications'));
    }

    public function show($id)
    {
        // Keep using Eloquent for show() because view doesn't have documents/payment
        $application = Application::with(['resident', 'premises.location', 'documents', 'payment', 'rentalAgreement', 'reviewer'])
            ->withTrashed()
            ->findOrFail($id);

        return view('admin.applications.show', compact('application'));
    }

    // Admin approves or rejects (no changes needed)
    public function review(ReviewApplicationRequest $request, $id)
    {
        $admin = auth('admin')->user();
        $application = Application::with('premises')->where('application_id', $id)->where('application_status', 'pending')->firstOrFail();

        if ($request->decision === 'approved') {
            // CHECK: Premises must still be available (prevents race condition)
            if ($application->premises->premises_status !== 'available') {
                return back()->with('error', 'Cannot approve: This premises is no longer available. ' . 'It has been rented to another applicant. ' . 'The application has been automatically rejected.');
            }

            // BEFORE approving, cancel all other pending applications for this resident
            $otherPendingApps = Application::with('premises')->where('resident_id', $application->resident_id)->where('application_id', '!=', $application->application_id)->where('application_status', 'pending')->get();

            $cancelledCount = 0;
            foreach ($otherPendingApps as $otherApp) {
                $otherApp->update([
                    'application_status' => 'cancelled',
                    'remarks' => 'Automatically cancelled because your application for "' . $application->premises->premises_name . '" was approved. MDCH policy allows only one active business license at a time.',
                    'reviewed_by' => $admin->admin_id,
                    'reviewed_at' => now(),
                    'updated_at' => now(),
                ]);

                // Send notification to resident about auto-cancellation
                NotificationService::applicationAutoCancelled($otherApp, $application->premises->premises_name);
                $cancelledCount++;
            }

            // Now approve the current application
            $application->update([
                'application_status' => 'approved',
                'reviewed_by' => $admin->admin_id,
                'reviewed_at' => now(),
                'remarks' => $request->remarks,
                'updated_at' => now(),
            ]);

            // Send approval notification
            NotificationService::applicationApproved($application);

            $msg = 'Application approved. ';
            if ($cancelledCount > 0) {
                $msg .= $cancelledCount . ' other pending application(s) have been automatically cancelled. ';
            }
            $msg .= 'Resident has been notified and may now proceed to payment.';
        } else {
            // Reject logic
            $application->update([
                'application_status' => 'rejected',
                'reviewed_by' => $admin->admin_id,
                'reviewed_at' => now(),
                'remarks' => $request->remarks,
                'updated_at' => now(),
            ]);

            NotificationService::applicationRejected($application, $request->remarks);
            $msg = 'Application rejected. Resident has been notified.';
        }

        return back()->with('success', $msg);
    }
}
