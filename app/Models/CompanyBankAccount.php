<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'bank_name', 'account_holder_name', 
        'account_number', 'ifsc_code', 'account_type', 'is_primary'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}