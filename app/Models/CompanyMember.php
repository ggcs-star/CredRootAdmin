<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'designation', 'pan_number', 
        'aadhaar_number', 'mobile', 'ownership_percentage'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}