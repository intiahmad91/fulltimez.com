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
        Schema::table('seeker_profiles', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('verification_status');
            $table->boolean('is_featured')->default(false)->after('approval_status');
            $table->timestamp('featured_expires_at')->nullable()->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            $table->dropColumn(['approval_status', 'is_featured', 'featured_expires_at']);
        });
    }
};
