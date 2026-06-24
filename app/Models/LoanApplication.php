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

 
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->application_number)) {
                $model->application_number = 'APP-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }


    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }
}