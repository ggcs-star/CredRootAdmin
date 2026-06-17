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
    Schema::create('document_masters', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // e.g., GST Certificate, 6 Months Bank Statement
        $table->string('entity_type')->nullable(); // Proprietorship, Pvt Ltd
        $table->boolean('is_mandatory')->default(true);
        $table->enum('collection_stage', ['pre_qualification', 'final_application']);
        $table->tinyInteger('status')->default(1);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_masters');
    }
};
