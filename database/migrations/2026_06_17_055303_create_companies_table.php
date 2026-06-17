<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('company_name')->nullable();
            
            // Enums ya strings for dropdowns
            $table->string('entity_type')->nullable(); // Proprietorship, Partnership, LLP, Pvt Ltd
            $table->string('industry_type')->nullable(); // Trading, Manufacturing, Service
            
            // Tax & Registration Docs
            $table->string('gst_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('udyam_registration_number')->nullable();
            
            $table->date('date_of_incorporation')->nullable();
            
            // Financials (Pre-qualification ke liye monthly_revenue zaroori hai)
            $table->decimal('monthly_revenue', 15, 2)->nullable(); 
            $table->decimal('turnover', 15, 2)->nullable();
            $table->decimal('annual_income', 15, 2)->nullable();
            
            // Address Details
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};