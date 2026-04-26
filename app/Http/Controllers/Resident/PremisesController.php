<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Premises;
use Illuminate\Http\Request;

class PremisesController extends Controller
{
    public function index(Request $request)
    {
        $query = Premises::with('location')->where('premises_status', 'available');

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('premises_type')) {
            $query->where('premises_type', $request->premises_type);
        }

        $premises = $query->orderBy('premises_name')->paginate(12);
        $locations = Location::orderBy('location_name')->get();
        $types = ['business_premises', 'market_table', 'market_stall', 'food_stall', 'handicraft', 'workshop', 'various'];

        return view('resident.premises.index', compact('premises', 'locations', 'types'));
    }

    public function show(Premises $premises)
    {
        $premises->load('location');
        return view('resident.premises.show', compact('premises'));
    }
}
