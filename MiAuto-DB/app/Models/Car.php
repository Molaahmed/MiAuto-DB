<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    public $timestamps = false;


    protected $guarded = [];
      /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'vin_number',
        'plate',
        'type',
        'fuel',
        'make',
        'model',
        'engine',
        'gear_box',
        'air_conditioner',
        'color',
    ];

}
