<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getCitiesByCountry($countryName)
    {
        try {
            // Find the country by name
            $country = Country::where('name', $countryName)
                             ->where('is_active', true)
                             ->first();

            if (!$country) {
                return response()->json([
                    'success' => false,
                    'message' => 'Country not found',
                    'cities' => []
                ], 404);
            }

            // Get active cities for this country
            $cities = City::where('country_id', $country->id)
                         ->where('is_active', true)
                         ->orderBy('name')
                         ->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'cities' => $cities
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching cities',
                'cities' => []
            ], 500);
        }
    }
}
