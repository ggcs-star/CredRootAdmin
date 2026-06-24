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
    Schema::table('company_bank_accounts', function (Blueprint $table) {
        // User ID add karein
        $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
        
        // Company ID ko nullable banayein (taaki bina company ke bhi bank add ho sake)
        $table->unsignedBigInteger('company_id')->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_bank_accounts', function (Blueprint $table) {
            //
        });
    }
};
