<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sign',
        'manufacturer',
        'model',
        'color',
        'image',
        'status',
        'deleted_at'];

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

    /**
     * @return HasOne
     */
    public function message(): HasOne
    {
        return $this->hasOne(LogMessage::class);
    }
}
