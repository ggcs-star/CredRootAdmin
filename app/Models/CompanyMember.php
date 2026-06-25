<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 
        'name', 
        'designation', 
        'pan_number', 
        'aadhaar_number', 
        'mobile', 
        'email',                     // NAYA
        'dob',                       // NAYA
        'din_number',                // NAYA
        'residential_address',       // NAYA
        'ownership_percentage',
        'is_authorized_signatory',   // NAYA
        'cibil_score'                // NAYA
    ];

    protected $casts = [
        'is_authorized_signatory' => 'boolean',
        'dob' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}