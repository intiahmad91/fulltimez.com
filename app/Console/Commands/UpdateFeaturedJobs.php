<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use Illuminate\Console\Command;

class UpdateFeaturedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:update-featured';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move expired featured jobs back to recommended section';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating featured jobs...');

        // Find jobs where featured_expires_at has passed
        $expiredFeaturedJobs = JobPosting::where('featured_expires_at', '<=', now())
            ->whereNotNull('featured_expires_at')
            ->get();

        if ($expiredFeaturedJobs->isEmpty()) {
            $this->info('No expired featured jobs found.');
            return;
        }

        $count = 0;
        foreach ($expiredFeaturedJobs as $job) {
            // Move job from featured to recommended (normal priority)
            $job->update([
                'featured_expires_at' => null,
                'priority' => 'normal',
            ]);
            
            $count++;
            $this->line("Moved job '{$job->title}' from featured to recommended section.");
        }

        $this->info("Successfully moved {$count} jobs from featured to recommended section.");
    }
}
