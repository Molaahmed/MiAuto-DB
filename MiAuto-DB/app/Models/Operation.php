<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
        'garage_id',
        'vin_number',
        'employee_id',
        'status',
        'date_entered',
        'date_exited',
        'cost'
    ];
}
