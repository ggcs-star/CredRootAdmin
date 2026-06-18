<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'bank_id',
        'loan_product_id',
        'application_number',
        'status_id',
        'remarks'
    ];

    /**
     * Boot method: Nayi application bante waqt automatically 
     * Application Number generate karne ke liye
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Agar application_number pehle se nahi diya gaya hai, toh auto-generate karo
            if (empty($model->application_number)) {
                // Example format: APP-9A2F4B
                $model->application_number = 'APP-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    // =======================
    // Relationships
    // =======================

    // Ye application kis Lead ki hai
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    // Ye application kis Bank mein bheji gayi hai
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    // Bank ke kis specific Loan Product ke liye hai (Optional/Nullable)
    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class);
    }

    // Is application ka current status kya hai (from lead_statuses table)
    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }
}