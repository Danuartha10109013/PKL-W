<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomisiM extends Model
{
    use HasFactory;

    protected $table = 'komisi_m';

    protected $fillable = [
        'no',
        'no_jobcard',
        'customer_name',
        'date',
        'no_po',
        'kurs',
        'gp',
        'it',
        'se',
        'as',
        'adm',
        'mng',
        'no_jo',
        'jo_date',
        'sales_name',
        'total_sp',
        'no_it',
    ];
}
