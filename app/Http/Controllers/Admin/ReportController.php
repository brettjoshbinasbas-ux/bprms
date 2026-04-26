<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\RentalAgreement;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Active agreements report (from view)
    public function activeAgreements()
    {
        $agreements = DB::table('vw_active_agreements')->paginate(15);
        return view('admin.reports.active-agreements', compact('agreements'));
    }

    // Revenue summary report (from view)
    public function revenueSummary()
    {
        $summary = DB::table('vw_revenue_summary')->get();
        $totalRevenue = $summary->sum('total_revenue');
        return view('admin.reports.revenue-summary', compact('summary', 'totalRevenue'));
    }

    // Application statistics report
    public function applicationStats()
    {
        $byStatus = Application::select('application_status', DB::raw('COUNT(*) as total'))->groupBy('application_status')->get();

        $byMonth = Application::select(DB::raw("DATE_FORMAT(application_date, '%Y-%m') as month"), DB::raw('COUNT(*) as total'))
            ->where('application_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.reports.application-stats', compact('byStatus', 'byMonth'));
    }

    // Occupancy report
    public function occupancy()
    {
        $occupied = DB::table('premises')->where('premises_status', 'occupied')->count();
        $available = DB::table('premises')->where('premises_status', 'available')->count();
        $unavailable = DB::table('premises')->where('premises_status', 'unavailable')->count();
        $total = $occupied + $available + $unavailable;

        $byType = DB::table('premises')->select('premises_type', 'premises_status', DB::raw('COUNT(*) as total'))->groupBy('premises_type', 'premises_status')->orderBy('premises_type')->get();

        return view('admin.reports.occupancy', compact('occupied', 'available', 'unavailable', 'total', 'byType'));
    }
}
