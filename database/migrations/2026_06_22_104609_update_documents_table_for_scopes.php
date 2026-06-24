<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // 1. Company ID add karein (Nullable, kyunki personal docs mein company nahi hogi)
            $table->foreignId('company_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('companies')
                  ->cascadeOnDelete();

            // 2. Lead ID ko nullable banayein (Taki bina loan apply kiye bhi doc upload ho sake)
            // Note: Ise chalane ke liye aapke paas 'doctrine/dbal' package hona chahiye (agar Laravel 10 se purana hai)
            $table->unsignedBigInteger('lead_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Reverse process
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            
            $table->unsignedBigInteger('lead_id')->nullable(false)->change();
        });
    }
};