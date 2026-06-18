<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Document extends Model {
    protected $fillable = [
        'user_id', 'lead_id', 'document_master_id', 'document_side','document_type', 
        'file_path', 'verification_status', 'verified_at'
    ];

    public function master() {
        return $this->belongsTo(DocumentMaster::class, 'document_master_id');
    }
}