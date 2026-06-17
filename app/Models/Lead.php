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

    /**
     * Boot function: Nayi lead banate waqt automatically Lead Number generate karne ke liye
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Agar controller se lead_number nahi aaya hai toh automatically assign karo
            if (empty($model->lead_number)) {
                $model->lead_number = 'LD-' . strtoupper(uniqid());
            }
        });
    }

    // =======================
    // Relationships
    // =======================

    // Lead belongs to a User (Customer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Lead belongs to a Company (Business)
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Lead has a specific Status (e.g., "Pending", "Approved")
    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }

    // Lead is assigned to an Admin/Agent (User)
    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Lead ke multiple documents ho sakte hain
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Lead ki multiple banks mein loan applications ho sakti hain
    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }
}