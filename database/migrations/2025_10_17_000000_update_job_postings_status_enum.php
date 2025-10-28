<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite, we need to recreate the table to modify the enum
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY COLUMN, so we'll recreate the table
            // First drop the index that includes status
            Schema::table('job_postings', function (Blueprint $table) {
                $table->dropIndex(['status', 'published_at']);
            });
            
            Schema::table('job_postings', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            
            Schema::table('job_postings', function (Blueprint $table) {
                $table->enum('status', ['draft', 'pending', 'published', 'rejected', 'closed'])->default('pending');
            });
            
            // Recreate the index
            Schema::table('job_postings', function (Blueprint $table) {
                $table->index(['status', 'published_at']);
            });
        } else {
            // For MySQL/MariaDB
            DB::statement("ALTER TABLE job_postings MODIFY COLUMN status ENUM('draft', 'pending', 'published', 'rejected', 'closed') DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Drop the index first
            Schema::table('job_postings', function (Blueprint $table) {
                $table->dropIndex(['status', 'published_at']);
            });
            
            Schema::table('job_postings', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            
            Schema::table('job_postings', function (Blueprint $table) {
                $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            });
            
            // Recreate the index
            Schema::table('job_postings', function (Blueprint $table) {
                $table->index(['status', 'published_at']);
            });
        } else {
            // Revert back to original enum values
            DB::statement("ALTER TABLE job_postings MODIFY COLUMN status ENUM('draft', 'published', 'closed') DEFAULT 'draft'");
        }
    }
};
