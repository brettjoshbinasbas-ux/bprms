<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident::withCount('applications');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(
                fn($q) => $q
                    ->where('resident_first_name', 'like', "%{$search}%")
                    ->orWhere('resident_last_name', 'like', "%{$search}%")
                    ->orWhere('resident_ic_number', 'like', "%{$search}%")
                    ->orWhere('resident_email', 'like', "%{$search}%"),
            );
        }

        $residents = $query->orderBy('resident_last_name')->paginate(15);
        return view('admin.residents.index', compact('residents'));
    }

    public function show(Resident $resident)
    {
        $resident->load(['applications.premises.location']);
        return view('admin.residents.show', compact('resident'));
    }
}
