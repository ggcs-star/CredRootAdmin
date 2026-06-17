<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'entity_type', 'industry_type', 
        'gst_number', 'pan_number', 'udyam_registration_number', 
        'date_of_incorporation', 'monthly_revenue', 'turnover', 
        'annual_income', 'address', 'city', 'state', 'pincode'
    ];

    // Ek company ke multiple members ho sakte hain
    public function members()
    {
        return $this->hasMany(CompanyMember::class);
    }
    public function bankAccounts()
    {
        return $this->hasMany(CompanyBankAccount::class);
    }
}