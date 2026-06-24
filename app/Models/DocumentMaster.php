<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentMaster extends Model {
    use HasFactory;

    protected $fillable = [
        'document_code', 'name', 'description', 
        'document_level', // Naya column add kiya
        'applicable_entities', 'applicable_loan_types', 
        'sides_required', 'allowed_formats', 'max_size_kb', 'sample_image_url',
        'is_mandatory', 'collection_stage', 'status'
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'applicable_entities' => 'array',
        'applicable_loan_types' => 'array',
    ];
}