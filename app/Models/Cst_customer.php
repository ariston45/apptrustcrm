<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cst_customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'created_by',
        'cst_id',
        'cst_institution',
        'cst_string_id',
        'cst_name',
        'cst_web',
        'cst_business_field',
        'cst_notes',
        'view_option',
    ];
}
