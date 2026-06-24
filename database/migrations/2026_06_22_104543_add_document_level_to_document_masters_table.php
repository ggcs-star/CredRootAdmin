<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_masters', function (Blueprint $table) {
            // Naya column add kar rahe hain 3 levels define karne ke liye
            $table->enum('document_level', ['user', 'company', 'lead'])
                  ->default('lead')
                  ->after('id')
                  ->comment('Defines if doc is for User profile, Company profile, or specific Loan Lead');
        });
    }

    public function down(): void
    {
        Schema::table('document_masters', function (Blueprint $table) {
            $table->dropColumn('document_level');
        });
    }
};