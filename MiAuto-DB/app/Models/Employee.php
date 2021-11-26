<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

        /**
         * Get the garage that owns the Employee
         *
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function garage(): BelongsTo
        {
            return $this->belongsTo(Garage::class,  'id', 'garage_id');
        }

        /**
         * Get the user associated with the Employee
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasOne
         */
        public function user(): HasOne
        {
            return $this->hasOne(User::class, 'id', 'user_id');
        }

        public $timestamps = false;

        protected $guarded = [];
}
