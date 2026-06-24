<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_number',
        'user_id',
        'company_id',
        'company_bank_account_id', 
        'loan_type_id',
        'loan_amount',

        'cibil_score',
        'average_bank_balance',
        'pre_approved_min_amount',
        'pre_approved_max_amount',
        'is_pre_qualified',
        
        'status_id',
        'assigned_to'
    ];

    protected $casts = [
        'is_pre_qualified' => 'boolean',
        'loan_amount' => 'decimal:2',
        'average_bank_balance' => 'decimal:2',
        'pre_approved_min_amount' => 'decimal:2',
        'pre_approved_max_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->lead_number)) {
                $model->lead_number = 'LD-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

 
    public function bankAccount()
    {
        return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
    }

    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }
    
    public function loanType()
    {
        return $this->belongsTo(LoanType::class, 'loan_type_id');
    }
}