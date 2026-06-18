<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model {
    use HasFactory;
    
    // Core Status Codes yahan define kar dein
    public const STATUS_NEW = 'NEW';
    public const STATUS_BANK_PROCESSING = 'BANK_PROCESSING';
    
    protected $fillable = ['name', 'internal_code', 'color', 'sort_order', 'is_system_locked'];
    
    protected $casts = [
        'is_system_locked' => 'boolean',
    ];
}