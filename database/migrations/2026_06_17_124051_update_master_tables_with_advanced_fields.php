<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update loan_types table
        Schema::table('loan_types', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('icon_path')->nullable()->after('description');
        });

        // 2. Update lead_statuses table
        Schema::table('lead_statuses', function (Blueprint $table) {
            $table->string('internal_code')->nullable()->unique()->after('name');
            $table->boolean('is_system_locked')->default(false)->after('sort_order');
        });

        // 3. Update document_masters table
        Schema::table('document_masters', function (Blueprint $table) {
            $table->string('document_code')->nullable()->unique()->after('id');
            $table->text('description')->nullable()->after('name');
            
            // JSON fields for dynamic logic
            $table->json('applicable_entities')->nullable()->after('description');
            $table->json('applicable_loan_types')->nullable()->after('applicable_entities');
            
            // UX / UI Controls
            $table->integer('sides_required')->default(1)->after('applicable_loan_types');
            $table->string('allowed_formats')->default('jpg,jpeg,png,pdf')->after('sides_required');
            $table->integer('max_size_kb')->default(5120)->after('allowed_formats'); // 5MB default
            $table->string('sample_image_url')->nullable()->after('max_size_kb');
            
            // Purana string column drop kar rahe hain kyunki ab hum JSON use karenge
            $table->dropColumn('entity_type');
        });
    }

    public function down(): void
    {
        // Rollback hone par kya hoga
        Schema::table('loan_types', function (Blueprint $table) {
            $table->dropColumn(['slug', 'icon_path']);
        });

        Schema::table('lead_statuses', function (Blueprint $table) {
            $table->dropColumn(['internal_code', 'is_system_locked']);
        });

        Schema::table('document_masters', function (Blueprint $table) {
            $table->dropColumn([
                'document_code', 'description', 'applicable_entities', 
                'applicable_loan_types', 'sides_required', 'allowed_formats', 
                'max_size_kb', 'sample_image_url'
            ]);
            $table->string('entity_type')->nullable()->after('name');
        });
    }
};