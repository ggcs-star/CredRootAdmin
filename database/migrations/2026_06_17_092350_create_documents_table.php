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
    Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
        $table->foreignId('document_master_id')->constrained('document_masters')->onDelete('cascade');
        
        $table->string('document_type')->nullable(); // mime type e.g., application/pdf, image/jpeg
        $table->string('file_path'); // S3 ya storage path
        
        $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
        $table->timestamp('verified_at')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
