<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            
            // Unique Lead ID (e.g., LD-12345)
            $table->string('lead_number')->unique();
            
            // Foreign Keys
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            
            // Loan type aur status nullable rakhe hain in case shuruaat mein pata na ho
            $table->unsignedBigInteger('loan_type_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            
            // Admin jisko assign hoga
            $table->unsignedBigInteger('assigned_to')->nullable();
            
            // Core Loan Amount
            $table->decimal('loan_amount', 15, 2);
            
            // -- PRE-QUALIFICATION FIELDS --
            $table->integer('cibil_score')->nullable();
            $table->decimal('average_bank_balance', 15, 2)->nullable();
            $table->decimal('pre_approved_min_amount', 15, 2)->nullable();
            $table->decimal('pre_approved_max_amount', 15, 2)->nullable();
            $table->boolean('is_pre_qualified')->default(false);
            // ------------------------------

            $table->timestamps();

            // Note: Agar aapne loan_types, lead_statuses aadi tables pehle se bana li hain, 
            // toh aap unhe strongly constrain kar sakte hain, jaise:
            // $table->foreign('status_id')->references('id')->on('lead_statuses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};