<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'loan_type_id',
        'product_name',
        'interest_rate',
        'processing_fee',
        'tenure_months',
        'min_amount',
        'max_amount',
        'status',
    ];

    protected $casts = [
        'interest_rate' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'status' => 'boolean', // TinyInteger 1/0 ko true/false mein map karega
    ];

    // =======================
    // Relationships
    // =======================

    // Ye product kis Bank ka hai
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    // Ye kis category (Working Capital, Term Loan) ka product hai
    public function loanType()
    {
        return $this->belongsTo(LoanType::class);
    }

    // Is product pe kitni applications aayi hain
    public function applications()
    {
        return $this->hasMany(LoanApplication::class);
    }
}