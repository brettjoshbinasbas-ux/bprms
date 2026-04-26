<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePremisesRequest;
use App\Models\Location;
use App\Models\Premises;

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
        Premises::create(array_merge($request->validated(), ['created_at' => now()]));
        return back()->with('success', 'Premises added successfully.');
    }

    public function update(StorePremisesRequest $request, Premises $premises)
    {
        $premises->update(array_merge($request->validated(), ['updated_at' => now()]));
        return back()->with('success', 'Premises updated successfully.');
    }

    public function destroy(Premises $premises)
    {
        $premises->delete();
        return back()->with('success', 'Premises deleted.');
    }
}
