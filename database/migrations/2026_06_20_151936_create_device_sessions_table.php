<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('device_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_device_id')->constrained('user_devices')->cascadeOnDelete();
            
            $table->string('refresh_token', 64)->unique(); 
            $table->string('ip_address', 45)->nullable(); 
            $table->text('user_agent')->nullable();
            
            $table->timestamp('expires_at')->index(); 
            $table->timestamp('last_used_at')->nullable();
            
            $table->timestamp('revoked_at')->nullable();
            $table->string('revoke_reason')->nullable(); 
            
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('device_sessions');
    }
};