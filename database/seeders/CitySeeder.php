<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $citiesData = [
            'United Arab Emirates' => [
                'Abu Dhabi', 'Dubai', 'Sharjah', 'Ajman', 'Ras Al Khaimah'
            ],
            'Saudi Arabia' => [
                'Riyadh', 'Jeddah', 'Mecca', 'Medina', 'Dammam'
            ],
            'Qatar' => [
                'Doha', 'Al Rayyan', 'Al Wakrah', 'Al Khor', 'Lusail'
            ],
            'Kuwait' => [
                'Kuwait City', 'Hawalli', 'Farwaniya', 'Ahmadi', 'Jahra'
            ],
            'Bahrain' => [
                'Manama', 'Riffa', 'Muharraq', 'Hamad Town', 'A\'ali'
            ],
            'Oman' => [
                'Muscat', 'Salalah', 'Sohar', 'Nizwa', 'Sur'
            ],
            'Other' => [
                'Other'
            ]
        ];

        foreach ($citiesData as $countryName => $cities) {
            $country = Country::where('name', $countryName)->first();
            
            if ($country) {
                foreach ($cities as $cityName) {
                    City::firstOrCreate(
                        [
                            'name' => $cityName,
                            'country_id' => $country->id
                        ],
                        [
                            'name' => $cityName,
                            'country_id' => $country->id,
                            'is_active' => true
                        ]
                    );
                }
            }
        }
    }
}
