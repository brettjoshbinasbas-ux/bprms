<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLocationRequest;
use App\Models\Location;

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
        $location->delete();
        return back()->with('success', 'Location deleted.');
    }
}
