<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard.index');
    }

    public function profile()
    {
        $user = auth()->user();
        
        // Show employer-specific profile view for employers
        if ($user->isEmployer()) {
            $countries = \App\Models\Country::where('is_active', true)->orderBy('name')->get();
            
            // Parse phone numbers to extract country code and number
            $phoneData = $this->parsePhoneNumber($user->phone ?? '');
            $mobileData = $this->parsePhoneNumber($user->employerProfile->mobile_no ?? '');
            $landlineData = $this->parsePhoneNumber($user->employerProfile->landline_no ?? '');
            
            return view('employer.profile', compact('user', 'countries', 'phoneData', 'mobileData', 'landlineData'));
        }
        
        return view('dashboard.profile', compact('user'));
    }
    
    /**
     * Parse phone number to extract country code and number
     */
    private function parsePhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return ['country_code' => '🇦🇪 +971', 'number' => ''];
        }
        
        // Common country codes mapping
        $countryCodeMap = [
            '+971' => '🇦🇪 +971',
            '+966' => '🇸🇦 +966',
            '+974' => '🇶🇦 +974',
            '+965' => '🇰🇼 +965',
            '+973' => '🇧🇭 +973',
            '+968' => '🇴🇲 +968',
            '+1' => '🇺🇸 +1',
            '+44' => '🇬🇧 +44',
            '+91' => '🇮🇳 +91',
            '+92' => '🇵🇰 +92',
            '+20' => '🇪🇬 +20',
        ];
        
        // Extract country code if it starts with +
        if (preg_match('/^(\+\d{1,4})\s*(.+)$/', $phoneNumber, $matches)) {
            $code = $matches[1];
            $number = $matches[2];
            $countryCode = $countryCodeMap[$code] ?? '🇦🇪 +971'; // Default to UAE
            return ['country_code' => $countryCode, 'number' => $number];
        }
        
        // If no country code found, assume UAE and treat entire as number
        return ['country_code' => '🇦🇪 +971', 'number' => $phoneNumber];
    }

    public function changePassword()
    {
        return view('dashboard.change-password');
    }
}

