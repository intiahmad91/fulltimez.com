<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\SeekerProfile;
use App\Models\EmployerProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $employerRole = Role::where('slug', 'employer')->first();
        $seekerRole = Role::where('slug', 'seeker')->first();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => config('app.admin_email', 'info@fulltimez.com'),
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'phone' => '+971 50 123 4567',
            'status' => 'active',
            'email_verified_at' => now(),
            'is_approved' => true, // Admin users are automatically approved
        ]);

        $employers = [
            [
                'name' => 'Tech Solutions LLC',
                'email' => 'hr@techsolutions.com',
                'phone' => '+971 50 234 5678',
                'company_name' => 'Tech Solutions LLC',
                'company_website' => 'https://techsolutions.com',
                'industry' => 'Information Technology',
                'company_size' => '50-100 employees',
                'founded_year' => 2015,
                'company_description' => 'Leading IT solutions provider in UAE specializing in software development, cloud services, and digital transformation.',
                'city' => 'Dubai',
                'country' => 'UAE',
            ],
            [
                'name' => 'Emirates Marketing Group',
                'email' => 'contact@emiratesmarketing.com',
                'phone' => '+971 50 345 6789',
                'company_name' => 'Emirates Marketing Group',
                'company_website' => 'https://emiratesmarketing.com',
                'industry' => 'Marketing & Advertising',
                'company_size' => '20-50 employees',
                'founded_year' => 2018,
                'company_description' => 'Full-service marketing agency providing digital marketing, branding, and social media management.',
                'city' => 'Abu Dhabi',
                'country' => 'UAE',
            ],
            [
                'name' => 'Global Engineering Corp',
                'email' => 'careers@globaleng.com',
                'phone' => '+971 50 456 7890',
                'company_name' => 'Global Engineering Corp',
                'company_website' => 'https://globaleng.com',
                'industry' => 'Engineering & Construction',
                'company_size' => '100-200 employees',
                'founded_year' => 2012,
                'company_description' => 'Premier engineering and construction company delivering world-class infrastructure projects.',
                'city' => 'Sharjah',
                'country' => 'UAE',
            ],
            [
                'name' => 'Healthcare Plus',
                'email' => 'jobs@healthcareplus.com',
                'phone' => '+971 50 567 8901',
                'company_name' => 'Healthcare Plus',
                'company_website' => 'https://healthcareplus.com',
                'industry' => 'Healthcare & Medical',
                'company_size' => '200+ employees',
                'founded_year' => 2010,
                'company_description' => 'Leading healthcare provider with multiple clinics and specialized medical services.',
                'city' => 'Dubai',
                'country' => 'UAE',
            ],
            [
                'name' => 'Finance Masters',
                'email' => 'recruitment@financemasters.com',
                'phone' => '+971 50 678 9012',
                'company_name' => 'Finance Masters',
                'company_website' => 'https://financemasters.com',
                'industry' => 'Finance & Accounting',
                'company_size' => '30-50 employees',
                'founded_year' => 2016,
                'company_description' => 'Professional accounting and financial consulting services for businesses across UAE.',
                'city' => 'Abu Dhabi',
                'country' => 'UAE',
            ],
        ];

        foreach ($employers as $employerData) {
            $employer = User::create([
                'name' => $employerData['name'],
                'email' => $employerData['email'],
                'password' => Hash::make('password'),
                'role_id' => $employerRole->id,
                'phone' => $employerData['phone'],
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            EmployerProfile::create([
                'user_id' => $employer->id,
                'company_name' => $employerData['company_name'],
                'company_website' => $employerData['company_website'],
                'industry' => $employerData['industry'],
                'company_size' => $employerData['company_size'],
                'founded_year' => $employerData['founded_year'],
                'company_description' => $employerData['company_description'],
                'city' => $employerData['city'],
                'country' => $employerData['country'],
                'contact_email' => $employerData['email'],
                'contact_phone' => $employerData['phone'],
                'verification_status' => 'verified',
            ]);
        }

        $seekers = [
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed.hassan@example.com',
                'phone' => '+971 50 111 2222',
                'full_name' => 'Ahmed Hassan',
                'gender' => 'male',
                'nationality' => 'UAE',
                'city' => 'Dubai',
                'current_position' => 'Senior Software Engineer',
                'experience_years' => '5-7 years',
                'expected_salary' => '15000-20000',
                'bio' => 'Experienced software engineer with expertise in Laravel, React, and cloud technologies. Passionate about building scalable applications.',
            ],
            [
                'name' => 'Fatima Al Mazrouei',
                'email' => 'fatima.almaz@example.com',
                'phone' => '+971 50 222 3333',
                'full_name' => 'Fatima Al Mazrouei',
                'gender' => 'female',
                'nationality' => 'UAE',
                'city' => 'Abu Dhabi',
                'current_position' => 'Marketing Manager',
                'experience_years' => '3-5 years',
                'expected_salary' => '12000-15000',
                'bio' => 'Creative marketing professional with proven track record in digital marketing and brand management.',
            ],
            [
                'name' => 'Mohammad Ali',
                'email' => 'mohammad.ali@example.com',
                'phone' => '+971 50 333 4444',
                'full_name' => 'Mohammad Ali',
                'gender' => 'male',
                'nationality' => 'Pakistan',
                'city' => 'Dubai',
                'current_position' => 'Graphic Designer',
                'experience_years' => '2-3 years',
                'expected_salary' => '8000-10000',
                'bio' => 'Creative graphic designer specializing in brand identity, UI/UX design, and digital illustrations.',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'phone' => '+971 50 444 5555',
                'full_name' => 'Sarah Johnson',
                'gender' => 'female',
                'nationality' => 'USA',
                'city' => 'Dubai',
                'current_position' => 'HR Manager',
                'experience_years' => '7-10 years',
                'expected_salary' => '18000-22000',
                'bio' => 'Experienced HR professional with expertise in recruitment, employee relations, and organizational development.',
            ],
            [
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh.kumar@example.com',
                'phone' => '+971 50 555 6666',
                'full_name' => 'Rajesh Kumar',
                'gender' => 'male',
                'nationality' => 'India',
                'city' => 'Sharjah',
                'current_position' => 'Accountant',
                'experience_years' => '4-6 years',
                'expected_salary' => '10000-13000',
                'bio' => 'Certified accountant with extensive experience in financial reporting, taxation, and audit.',
            ],
            [
                'name' => 'Layla Mohammed',
                'email' => 'layla.mohammed@example.com',
                'phone' => '+971 50 666 7777',
                'full_name' => 'Layla Mohammed',
                'gender' => 'female',
                'nationality' => 'UAE',
                'city' => 'Dubai',
                'current_position' => 'Content Writer',
                'experience_years' => '2-4 years',
                'expected_salary' => '7000-9000',
                'bio' => 'Creative content writer and copywriter with expertise in digital content, SEO writing, and social media.',
            ],
            [
                'name' => 'Omar Abdullah',
                'email' => 'omar.abdullah@example.com',
                'phone' => '+971 50 777 8888',
                'full_name' => 'Omar Abdullah',
                'gender' => 'male',
                'nationality' => 'Egypt',
                'city' => 'Abu Dhabi',
                'current_position' => 'Sales Executive',
                'experience_years' => '3-5 years',
                'expected_salary' => '9000-12000',
                'bio' => 'Results-driven sales professional with strong communication skills and proven sales record.',
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya.sharma@example.com',
                'phone' => '+971 50 888 9999',
                'full_name' => 'Priya Sharma',
                'gender' => 'female',
                'nationality' => 'India',
                'city' => 'Dubai',
                'current_position' => 'UI/UX Designer',
                'experience_years' => '3-5 years',
                'expected_salary' => '11000-14000',
                'bio' => 'User-focused designer creating beautiful and intuitive digital experiences.',
            ],
            [
                'name' => 'Misbah Muhammad',
                'email' => 'misbah.muhammad@example.com',
                'phone' => '+971 50 999 1111',
                'full_name' => 'Misbah Muhammad',
                'gender' => 'male',
                'nationality' => 'Pakistan',
                'city' => 'Abu Dhabi',
                'current_position' => 'Digital Marketing Specialist',
                'experience_years' => '1-2 years',
                'expected_salary' => 'Negotiable',
                'bio' => 'Digital marketing expert specializing in social media marketing, content strategy, and SEO.',
            ],
            [
                'name' => 'Aisha Khan',
                'email' => 'aisha.khan@example.com',
                'phone' => '+971 50 111 3333',
                'full_name' => 'Aisha Khan',
                'gender' => 'female',
                'nationality' => 'UAE',
                'city' => 'Dubai',
                'current_position' => 'Project Manager',
                'experience_years' => '6-8 years',
                'expected_salary' => '16000-20000',
                'bio' => 'Certified project manager with experience in leading cross-functional teams and delivering complex projects.',
            ],
        ];

        foreach ($seekers as $seekerData) {
            $seeker = User::create([
                'name' => $seekerData['name'],
                'email' => $seekerData['email'],
                'password' => Hash::make('password'),
                'role_id' => $seekerRole->id,
                'phone' => $seekerData['phone'],
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            SeekerProfile::create([
                'user_id' => $seeker->id,
                'full_name' => $seekerData['full_name'],
                'gender' => $seekerData['gender'],
                'nationality' => $seekerData['nationality'],
                'city' => $seekerData['city'],
                'current_position' => $seekerData['current_position'],
                'experience_years' => $seekerData['experience_years'],
                'expected_salary' => $seekerData['expected_salary'],
                'bio' => $seekerData['bio'],
                'availability' => 'immediate',
                'skills' => json_encode(['Communication', 'Teamwork', 'Problem Solving']),
                'languages' => json_encode(['English', 'Arabic']),
            ]);
        }
    }
}

