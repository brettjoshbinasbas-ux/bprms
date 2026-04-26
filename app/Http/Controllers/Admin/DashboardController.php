<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Premises;
use App\Models\Resident;
use App\Models\RentalAgreement;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalApplications = Application::count();
        $pendingApplications = Application::where('application_status', 'pending')->count();
        $totalResidents = Resident::count();
        $activeAgreements = RentalAgreement::where('agreement_status', 'active')->count();

        // Applications submitted in last 7 days grouped by status
        $applicationsByDay = Application::select(DB::raw('DATE(application_date) as day'), 'application_status', DB::raw('COUNT(*) as total'))
            ->where('application_date', '>=', now()->subDays(6))
            ->groupBy('day', 'application_status')
            ->orderBy('day')
            ->get();

        $recentApplications = Application::with(['resident', 'premises.location'])
            ->orderByDesc('application_date')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('totalApplications', 'pendingApplications', 'totalResidents', 'activeAgreements', 'applicationsByDay', 'recentApplications'));
    }
}
