<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use Illuminate\Console\Command;

class UpdateJobPriority extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:update-priority';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move jobs from featured to recommended section after 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating job priorities...');
        
        // Find jobs that were featured but the featured period has expired
        $expiredFeaturedJobs = JobPosting::where('status', 'published')
            ->where('featured_expires_at', '<=', now())
            ->whereNotNull('featured_expires_at')
            ->get();

        $count = 0;
        
        foreach ($expiredFeaturedJobs as $job) {
            // Update the job to remove featured status
            $job->update([
                'featured_expires_at' => null
            ]);
            
            $count++;
            $this->line("Moved job '{$job->title}' from featured to recommended section.");
        }

        $this->info("Successfully moved {$count} jobs from featured to recommended section.");
        
        // Also check for expired jobs and close them
        $expiredJobs = JobPosting::where('status', 'published')
            ->where('expires_at', '<=', now())
            ->get();

        $expiredCount = 0;
        foreach ($expiredJobs as $job) {
            $job->update(['status' => 'closed']);
            $expiredCount++;
            $this->line("Closed expired job '{$job->title}'.");
        }

        if ($expiredCount > 0) {
            $this->info("Closed {$expiredCount} expired jobs.");
        }

        return Command::SUCCESS;
    }
}
