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
    Schema::create('document_verifications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
        
        $table->string('provider'); // e.g., 'APIClub'
        $table->json('request_payload')->nullable(); // API ko kya bheja
        $table->json('response_payload')->nullable(); // API se kya aaya
        $table->string('verification_status')->nullable(); // e.g., VALID, INVALID
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_verifications');
    }
};
