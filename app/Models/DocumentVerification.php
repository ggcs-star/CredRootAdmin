<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DocumentVerification extends Model {
    protected $fillable = [
        'document_id', 'provider', 'request_payload', 
        'response_payload', 'verification_status'
    ];

    // JSON casting bohot zaroori hai taaki array format mein data mile
    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
    ];

    public function document() {
        return $this->belongsTo(Document::class);
    }
}