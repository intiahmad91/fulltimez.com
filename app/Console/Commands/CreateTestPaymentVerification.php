<?php

namespace App\Console\Commands;

use App\Models\PaymentVerification;
use App\Models\User;
use App\Models\Package;
use App\Models\Role;
use App\Notifications\PaymentVerificationRequest;
use Illuminate\Console\Command;

class CreateTestPaymentVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-payment-verification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test payment verification and notify admins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get first employer
        $employer = User::where('role_id', 2)->first();
        if (!$employer) {
            $this->error('No employer found');
            return;
        }

        // Get first package
        $package = Package::first();
        if (!$package) {
            $this->error('No package found');
            return;
        }

        // Create payment verification
        $payment = PaymentVerification::create([
            'employer_id' => $employer->id,
            'package_id' => $package->id,
            'package_type' => 'premium',
            'amount' => 299,
            'currency' => 'AED',
            'payment_method' => 'bank_transfer',
            'status' => 'pending',
            'payment_notes' => 'Test payment verification created by command'
        ]);

        $this->info("Created payment verification ID: {$payment->id}");

        // Notify admins
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $adminUsers = User::where('role_id', $adminRole->id)->get();
            $this->info("Found {$adminUsers->count()} admin users");

            foreach ($adminUsers as $admin) {
                $admin->notify(new PaymentVerificationRequest(
                    $payment->id,
                    $employer->name,
                    $employer->employerProfile->company_name ?? 'Test Company',
                    $package->name,
                    $payment->amount,
                    $payment->currency,
                    'Test Job'
                ));
                $this->info("Notification sent to admin: {$admin->name}");
            }
        } else {
            $this->error('Admin role not found');
        }

        $this->info('Test payment verification created successfully!');
    }
}
