<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Car extends Model
{
    protected $fillable = ['user_id', 'sign', 'manufacturer', 'model', 'color', 'image', 'status'];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id');
    }

    /**
     * @return HasOne
     */
    public function parkingSpot(): HasOne
    {
        return $this->hasOne(ParkingSpot::class);
    }
}
