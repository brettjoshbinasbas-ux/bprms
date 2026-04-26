<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $resident = auth('resident')->user();

        $totalApplications = Application::where('resident_id', $resident->resident_id)->count();

        $pendingApplications = Application::where('resident_id', $resident->resident_id)->where('application_status', 'pending')->count();

        $approvedApplications = Application::where('resident_id', $resident->resident_id)->where('application_status', 'approved')->count();

        $recentApplications = Application::with(['premises.location'])
            ->where('resident_id', $resident->resident_id)
            ->orderByDesc('application_date')
            ->limit(5)
            ->get();

        return view('resident.dashboard', compact('totalApplications', 'pendingApplications', 'approvedApplications', 'recentApplications'));
    }
}
