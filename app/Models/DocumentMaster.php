<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentMaster extends Model {
    use HasFactory;

    protected $fillable = [
        'document_code', 'name', 'description', 
        'applicable_entities', 'applicable_loan_types', 
        'sides_required', 'allowed_formats', 'max_size_kb', 'sample_image_url',
        'is_mandatory', 'collection_stage', 'status'
    ];

    // JSON column ko Array mein automatically convert karne ke liye 'array' cast use karna zaroori hai
    protected $casts = [
        'is_mandatory' => 'boolean',
        'applicable_entities' => 'array',
        'applicable_loan_types' => 'array',
    ];
}