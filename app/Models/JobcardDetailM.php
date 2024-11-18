<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobcardDetailM extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $table = 'jobcard_detail';
}
