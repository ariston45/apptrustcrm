<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cst_institution extends Model
{
    use HasFactory;
    protected $fillable = [
        'ins_id',
        'ins_name',
        'created_by',
        'ins_note',
        'ins_business_field',
        'cst_string_id',
        'ins_web',
    ];
}
