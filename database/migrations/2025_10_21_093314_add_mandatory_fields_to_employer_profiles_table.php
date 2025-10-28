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
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->string('mobile_no')->nullable()->after('country');
            $table->string('email_id')->nullable()->after('mobile_no');
            $table->string('landline_no')->nullable()->after('email_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->dropColumn(['mobile_no', 'email_id', 'landline_no']);
        });
    }
};
