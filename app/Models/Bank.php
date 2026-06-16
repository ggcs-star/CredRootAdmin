<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'logo',

        'contact_person',
        'email',
        'phone',

        'min_loan_amount',
        'max_loan_amount',

        'interest_rate_from',
        'interest_rate_to',

        'max_tenure_months',

        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'min_loan_amount' => 'decimal:2',
        'max_loan_amount' => 'decimal:2',
        'interest_rate_from' => 'decimal:2',
        'interest_rate_to' => 'decimal:2',
    ];
}
