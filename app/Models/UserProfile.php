<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'dob', 'gender', 'aadhaar_number', 
        'pan_number', 'occupation', 'address', 
        'city', 'state', 'pincode'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}