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
        // 1. Updating 'companies' table
        Schema::table('companies', function (Blueprint $table) {
            $table->string('cin_number', 21)->nullable()->after('industry_type')->comment('Only for Pvt/Pub Ltd');
            $table->string('company_email')->nullable()->after('udyam_registration_number');
            $table->string('company_phone', 20)->nullable()->after('company_email');
        });

        // 2. Updating 'company_members' table
        Schema::table('company_members', function (Blueprint $table) {
            $table->string('email')->nullable()->after('mobile');
            $table->date('dob')->nullable()->after('email');
            $table->string('din_number', 8)->nullable()->after('dob')->comment('Director Identification Number');
            $table->text('residential_address')->nullable()->after('din_number');
            $table->boolean('is_authorized_signatory')->default(false)->after('ownership_percentage');
            $table->integer('cibil_score')->nullable()->after('is_authorized_signatory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Removing columns from 'companies' table
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'cin_number',
                'company_email',
                'company_phone'
            ]);
        });

        // 2. Removing columns from 'company_members' table
        Schema::table('company_members', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'dob',
                'din_number',
                'residential_address',
                'is_authorized_signatory',
                'cibil_score'
            ]);
        });
    }
};