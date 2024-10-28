<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomisiM extends Model
{
    use HasFactory;

    // Specify the table associated with the model if not following Laravel's naming convention
    protected $table = 'komisi';

    // Define the fillable attributes
    protected $fillable = [
        'no_jobcard',
        'customer_name',
        'date',
        'no_po',
        'kurs',
        'totalbop',
        'totalsp',
        'totalbp',
        'po_date',
        'po_received',
        'no_form',
        'effective_date',
        'no_revisi',
    ];

    // Optionally, you can define relationships here if needed
}
