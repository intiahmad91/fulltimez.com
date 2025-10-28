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
        Schema::table('employer_documents', function (Blueprint $table) {
            $table->string('company_website')->nullable()->after('company_email');
            $table->string('contact_person_name')->nullable()->after('company_website');
            $table->string('contact_person_mobile')->nullable()->after('contact_person_name');
            $table->string('contact_person_position')->nullable()->after('contact_person_mobile');
            $table->string('contact_person_email')->nullable()->after('contact_person_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employer_documents', function (Blueprint $table) {
            $table->dropColumn(['company_website', 'contact_person_name', 'contact_person_mobile', 'contact_person_position', 'contact_person_email']);
        });
    }
};
