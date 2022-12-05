<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use LaravelIdea\Helper\App\Models\_IH_ParkingSpot_C;

class ParkingSpot extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'car_id', 'number', 'row', 'image', 'status'];

    /**
     * @return ParkingSpot[]|_IH_ParkingSpot_C
     */
    public static function getAllParkingSpotsWithCars()
    {
        return ParkingSpot::with('car')->get();
//        return ParkingSpot::select(
//            'parking_spots.id',
//            'parking_spots.user_id',
//            'parking_spots.user_id',
//            'parking_spots.number',
//            'parking_spots.row',
//            'parking_spots.status',
//            'cars.sign',
//            'cars.image'
//        )
//            ->join('cars', 'parking_spots.car_id', '=', 'cars.id', 'left outer')
//            ->get();
    }


    /**
     * switch the CSS-Style for Buttons
     * @return string CSS-Style
     */
    public function switchStatus(): string
    {
        return match ($this->status) {
            'frei', 'Behindertenparkplatz' => 'btn-success',
            'electro' => 'btn-info',
            'reserviert' => 'btn-warning',
            'besetzt' => 'btn-outline-danger',
            'gesperrt' => 'btn-danger',
            default => 'alert-dark ',
        };
    }


    /**
     * switch the Output for ButtonText
     * @return string ButtonText
     */
    public function getStatusMessage(): string
    {
        return match ($this->status) {
            'frei', 'electro', 'Behindertenparkplatz' => ' - derzeit frei',
            'reserviert', 'besetzt', 'gesperrt' => $this->status . ' - Reservierung nicht möglich',
            default => ' !!! Parkplatzstatus ungültig! Informieren SIe einen Administrator !!!',
        };
    }


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault([
            'user_id' => '1'
        ]);
    }


    /**
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
