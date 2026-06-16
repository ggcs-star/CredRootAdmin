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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();

            // Basic Details
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('logo')->nullable();

            // Contact Details
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();

            // Business Loan Details
            $table->decimal('min_loan_amount', 15, 2)->nullable();
            $table->decimal('max_loan_amount', 15, 2)->nullable();

            $table->decimal('interest_rate_from', 5, 2)->nullable();
            $table->decimal('interest_rate_to', 5, 2)->nullable();

            $table->integer('max_tenure_months')->nullable();

            // Status
            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->index('name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};