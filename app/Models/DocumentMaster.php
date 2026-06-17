<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DocumentMaster extends Model {
    protected $fillable = ['name', 'entity_type', 'is_mandatory', 'collection_stage', 'status'];
    protected $casts = ['is_mandatory' => 'boolean'];
}