<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParkingSpotController extends Controller
{
    public function index(): Factory|View|Application
    {
        $viewData = [];
        $viewData["title"] = "Parkplatzverwaltung";
        $viewData["subtitle"] = "Parkplatzübersicht";
        $viewData["parking_spots"] = ParkingSpot::all();
        return view('parking_spots.index')->with("viewData", $viewData);
    }

    public function show($id): Factory|View|Application
    {
        $parking_spot = ParkingSpot::findOrFail($id);
        $viewData = [];

        $viewData["user"] = User::findOrFail(Auth::id());
        $viewData["title"] = "Parkplatzansicht";
        $viewData["subtitle"] = "Parkplatz Nr. " . $parking_spot->getNumber();
        $viewData["parking_spot"] = $parking_spot;

        $car = Car::with('parkingSpot')
            ->select('cars.sign', 'cars.image', 'cars.manufacturer', 'cars.model', 'cars.color')
            ->where('cars.user_id', Auth::id())
            ->join('parking_spots', 'parking_spots.user_id', '=', 'cars.user_id')
            ->where( 'cars.id', '!=', Auth::id())
            ->distinct()
            ->get();

        $viewData["cars"] = $car;
        return view('parking_spots.show')->with("viewData", $viewData);
    }

    public function storeIndex(Request $request): Factory|View|Application
    {
        $viewData = [];
        $viewData['title'] = 'Reserve a parking spot - Parkplatzverwaltung';
        $viewData['subtitle'] = 'Reserve a parking spot - Parkplatzverwaltung';

        $selected_ps_number = substr($request->getRequestUri(), -1);

        $viewData['user'] = User::all()->where('id', Auth::id())->first();
        $parking_spot = ParkingSpot::all()->where('number', $selected_ps_number);
        $car = Car::all()->where('user_id', Auth::id());

        $viewData['parking_spot'] = $parking_spot;
        $viewData['cars'] = $car;

        $id = $car->first()->getAttribute('id');
        $ps_id = $parking_spot->first()->getAttribute('id');

        ParkingSpot::findOrFail($ps_id)->update([
            'user_id' => Auth::id(),
            'car_id' => $id,
            'image' => 'reserviert.jpg',
            'status' => 'reserviert'
        ]);

        return view('parking_spots.reserve.store_reserve')->with("viewData", $viewData);
    }


    public function store(Request $request): Factory|View|Application
    {
        $viewData = [];
        $viewData['title'] = 'Reserve a parking spot - Parkplatzverwaltung';
        $viewData['subtitle'] = 'Reserve a parking spot - Parkplatzverwaltung';

        ParkingSpot::validate($request);

        $parking_spot_id = $request->get('radio');

        $parking_spot_user = ParkingSpot::findOrFail($parking_spot_id);
        $parking_spot_user->setId($parking_spot_id);
        $parking_spot_user->setUserId(Auth::id());
        $parking_spot_user->setStatus('reserviert');
        $parking_spot_user->setImage('reserviert.jpg');
        $parking_spot_user->update();

        return view('user.show', [Auth::id()])->with("viewData", $viewData);
    }

    public function storeThisCar(Request $request): Factory|View|Application
    {
//       dd($request);
        $viewData = [];
        $viewData['title'] = 'Reserve a parking spot - Parkplatzverwaltung';
        $viewData['subtitle'] = 'Reserve a parking spot - Parkplatzverwaltung';
        $viewData['user'] = Auth::id();

        $parking_spot_id = $request['status'];
        $parking_spot_user = ParkingSpot::findOrFail($parking_spot_id);
        $parking_spot_user->setId($parking_spot_id);
        $parking_spot_user->setUserId(Auth::id());
        $parking_spot_user->setStatus('reserviert');
        $parking_spot_user->setImage('reserviert.jpg');

        $parking_spot_user->update();

        return view('user.show', [Auth::id()])->with("viewData", $viewData);
    }
}
