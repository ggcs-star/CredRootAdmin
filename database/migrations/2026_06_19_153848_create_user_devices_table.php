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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->uuid('id')->primary(); // HasUuids use karenge hum model mein
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Nullable kyunki pehli baar bina login ke bhi device track ho sakta hai
            
            // Device Identity
            $table->string('device_id')->index();
            $table->string('fingerprint_hash')->index();
            $table->string('device_name')->nullable();
            $table->string('device_type')->nullable(); // MOBILE, DESKTOP, TABLET, BOT, EMULATOR
            $table->string('browser')->nullable();
            $table->string('platform')->nullable(); // Windows, Android, iOS
            $table->string('app_version')->nullable();
            $table->string('language')->nullable();
            $table->text('user_agent')->nullable();
            
            // Location & Network
            $table->string('last_ip_address')->nullable();
            $table->string('last_country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('last_city')->nullable();
            $table->string('timezone')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lon', 11, 8)->nullable();
            
            // Security & Risk Engine
            $table->string('trust_level')->default('NEW'); // NEW, VERIFIED, TRUSTED, SUSPICIOUS, BLOCKED
            $table->integer('risk_score')->default(0);
            $table->json('risk_reason')->nullable(); // Array save karne ke liye JSON column
            $table->boolean('vpn_detected')->default(false);
            $table->boolean('proxy_detected')->default(false);
            $table->boolean('is_emulator')->default(false);
            $table->integer('failed_attempts')->default(0);
            
            // Activity Logs
            $table->integer('login_count')->default(0);
            $table->boolean('is_current')->default(false);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('trusted_at')->nullable();
            $table->timestamp('blocked_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};