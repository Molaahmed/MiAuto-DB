<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garage extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone_number',
        'email'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the operations for the Garage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operations()
    {
        return $this->hasMany(Operation::class_uses);
    }
    
    
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get all of the employee for the Garage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employee()
    {
        return $this->hasMany(Employee::class, 'garage_id', 'id');
    }

}
