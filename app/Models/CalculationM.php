<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculationM extends Model
{
    use HasFactory;

    protected $table = 'calculation';
    protected $fillable = [
        'it',
        'se',
        'as',
        'adm',
        'mng',
        'jenis',
    ];
}
