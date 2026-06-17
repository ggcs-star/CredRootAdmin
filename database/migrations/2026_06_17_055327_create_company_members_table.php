<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            
            $table->string('name')->nullable();
            $table->string('designation')->nullable(); // Director, Partner, Proprietor
            
            $table->string('pan_number')->nullable();
            $table->string('aadhaar_number')->nullable();
            $table->string('mobile')->nullable();
            
            $table->decimal('ownership_percentage', 5, 2)->nullable(); // e.g., 50.00
            
            $table->timestamps(); // DBML mein updated_at nahi tha, par Laravel mein by default dono aate hain
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_members');
    }
};