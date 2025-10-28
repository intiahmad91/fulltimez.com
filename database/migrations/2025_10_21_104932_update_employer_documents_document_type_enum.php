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
            $table->enum('document_type', ['trade_license', 'office_landline', 'company_email', 'company_info'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employer_documents', function (Blueprint $table) {
            $table->enum('document_type', ['trade_license', 'office_landline', 'company_email'])->change();
        });
    }
};
