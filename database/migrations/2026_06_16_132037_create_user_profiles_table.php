<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            // user_id ko foreign key banaya aur cascade delete lagaya
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            
            // Aadhaar aur PAN unique hone chahiye
            $table->string('aadhaar_number')->unique()->nullable();
            $table->string('pan_number')->unique()->nullable();
            
            $table->string('occupation')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};