<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic Package',
                'description' => 'Perfect for small businesses starting their hiring journey',
                'price' => 10.00,
                'duration_days' => 7,
                'job_posts_limit' => 3,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Standard Package',
                'description' => 'Ideal for growing companies with regular hiring needs',
                'price' => 20.00,
                'duration_days' => 15,
                'job_posts_limit' => 10,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium Package',
                'description' => 'Best value for established companies with high hiring volume',
                'price' => 30.00,
                'duration_days' => 30,
                'job_posts_limit' => null, // unlimited
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise Package',
                'description' => 'For large organizations with extensive hiring requirements',
                'price' => 50.00,
                'duration_days' => 60,
                'job_posts_limit' => null, // unlimited
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($packages as $package) {
            \App\Models\Package::create($package);
        }
    }
}
