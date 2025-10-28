<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with full system access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employer',
                'slug' => 'employer',
                'description' => 'Employer who can post jobs and manage applications',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Seeker',
                'slug' => 'seeker',
                'description' => 'Job seeker who can apply for jobs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}



