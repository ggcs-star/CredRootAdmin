<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->foreignId('bank_id')->constrained('banks')->onDelete('cascade');
            
            // Nullable rakha hai kyunki user shuru mein sirf bank select karta hai, exact product baad mein decide ho sakta hai
            $table->foreignId('loan_product_id')->nullable()->constrained('loan_products')->nullOnDelete();
            
            // Unique Application Number (e.g., APP-12345)
            $table->string('application_number')->unique();
            
            // Status track karne ke liye
            $table->foreignId('status_id')->nullable()->constrained('lead_statuses')->nullOnDelete();
            
            $table->text('remarks')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};