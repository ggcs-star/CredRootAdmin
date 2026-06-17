<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LoanType extends Model {
    protected $fillable = ['name', 'description', 'status'];
}