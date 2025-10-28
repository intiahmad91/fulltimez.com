<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('job_categories')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('benefits')->nullable();
            $table->enum('employment_type', ['full-time', 'part-time', 'contract', 'freelance', 'internship'])->default('full-time');
            $table->enum('experience_level', ['entry', 'mid', 'senior', 'executive'])->default('entry');
            $table->string('experience_years')->nullable();
            $table->string('education_level')->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('salary_currency', 3)->default('USD');
            $table->string('salary_period')->default('monthly');
            $table->boolean('salary_negotiable')->default(false);
            $table->string('location_city');
            $table->string('location_state')->nullable();
            $table->string('location_country');
            $table->boolean('remote_allowed')->default(false);
            $table->integer('positions_available')->default(1);
            $table->date('application_deadline')->nullable();
            $table->json('skills_required')->nullable();
            $table->json('languages_required')->nullable();
            $table->enum('status', ['draft', 'pending', 'published', 'closed'])->default('draft');
            $table->enum('priority', ['normal', 'premium', 'premium_15', 'premium_30'])->default('normal');
            $table->integer('views_count')->default(0);
            $table->integer('applications_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'published_at']);
            $table->index(['employment_type', 'experience_level']);
            $table->index(['location_city', 'location_country']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};