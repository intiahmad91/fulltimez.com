<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::withCount('cities')->latest()->paginate(20);
        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
            'code' => 'required|string|max:3|unique:countries,code',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Country::create($data);

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country created successfully!');
    }

    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
            'code' => 'required|string|max:3|unique:countries,code,' . $country->id,
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $country->update($data);

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country updated successfully!');
    }

    public function destroy(Country $country)
    {
        // Check if country has cities or jobs
        if ($country->cities()->count() > 0 || $country->jobPostings()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete country. It has associated cities or jobs.');
        }

        $country->delete();

        return redirect()->back()
            ->with('success', 'Country deleted successfully!');
    }
}
