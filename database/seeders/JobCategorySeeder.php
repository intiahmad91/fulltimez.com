<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Travel & Tourism / Leisure',
            'IT & Technology',
            'Healthcare & Medical',
            'Engineering',
            'Sales & Marketing',
            'Finance & Accounting',
            'Human Resources',
            'Customer Service',
            'Education & Training',
            'Construction & Real Estate',
            'Digital / Social Media Marketing',
            'Hospitality',
            'Administration',
            'Legal Services',
        ];

        foreach ($categories as $category) {
            DB::table('job_categories')->insert([
                'name' => $category,
                'slug' => Str::slug($category),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}



