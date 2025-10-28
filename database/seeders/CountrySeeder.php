<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'United Arab Emirates', 'code' => 'UAE', 'is_active' => true],
            ['name' => 'Saudi Arabia', 'code' => 'SAU', 'is_active' => true],
            ['name' => 'Qatar', 'code' => 'QAT', 'is_active' => true],
            ['name' => 'Kuwait', 'code' => 'KWT', 'is_active' => true],
            ['name' => 'Bahrain', 'code' => 'BHR', 'is_active' => true],
            ['name' => 'Oman', 'code' => 'OMN', 'is_active' => true],
            ['name' => 'Other', 'code' => 'OTH', 'is_active' => true],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(
                ['name' => $country['name']],
                $country
            );
        }
    }
}
