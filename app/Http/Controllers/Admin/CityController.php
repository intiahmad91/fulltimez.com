<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with('country');

        if ($request->has('country_id') && $request->country_id != '') {
            $query->where('country_id', $request->country_id);
        }

        $perPage = $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        
        $cities = $query->latest()->paginate($perPage);
        $countries = Country::where('is_active', true)->get();

        return view('admin.cities.index', compact('cities', 'countries'));
    }

    public function create()
    {
        $countries = Country::where('is_active', true)->get();
        return view('admin.cities.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        City::create($data);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully!');
    }

    public function edit(City $city)
    {
        $countries = Country::where('is_active', true)->get();
        return view('admin.cities.edit', compact('city', 'countries'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $city->update($data);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully!');
    }

    public function destroy(City $city)
    {
        // Check if city has jobs
        if ($city->jobPostings()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete city. It has associated jobs.');
        }

        $city->delete();

        return redirect()->back()
            ->with('success', 'City deleted successfully!');
    }
}
