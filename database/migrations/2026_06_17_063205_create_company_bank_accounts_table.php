<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_bank_accounts', function (Blueprint $table) {
            $table->id();
            
            // Ye account kis company ka hai
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            
            $table->string('bank_name'); // e.g., HDFC, SBI, ICICI
            $table->string('account_holder_name');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->enum('account_type', ['Current', 'Savings', 'OD/CC']);
            $table->boolean('is_primary')->default(true); // Agar multiple accounts hon toh main kaunsa hai
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_bank_accounts');
    }
};