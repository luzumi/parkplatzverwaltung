<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelIdea\Helper\App\Models\_IH_ParkingSpot_C;

/**
 * @method static findOrFail($id)
 * @method static create(array $creationData)
 * @method static where(string $string, string $selected_ps_number)
 */
class ParkingSpot extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'car_id', 'number', 'row', 'image', 'status'];

    /**
     * @return ParkingSpot[]|_IH_ParkingSpot_C
     */
    public static function getAllParkingSpotsWithCars(): array|_IH_ParkingSpot_C
    {
        return ParkingSpot::with('car')->get();
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
            'reserviert', 'besetzt', 'gesperrt' =>  $this->status . ' - Reservierung nicht möglich',
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
     * @return HasOne
     */
    public function car(): HasOne
    {
        return $this->hasOne(Car::class);
    }
}
