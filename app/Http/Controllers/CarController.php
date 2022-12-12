<?php

namespace App\Http\Controllers;

use App\Actions\CreateMessage;
use App\Actions\CreateNewCar;
use App\Actions\SetImageName;
use App\Enums\MessageType;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $viewData = [];
        $viewData['title'] = 'Parkplatzverwaltung';
        $viewData['subtitle'] = 'Fahrzeugübersicht';
        $viewData['cars'] = Car::all()->where('deleted_at', null);

        return view('cars.index')->with('viewData', $viewData);
    }

    /**
     * @return Factory|View|Application
     */
    public function storeIndex(): Factory|View|Application
    {
        $viewData = [];
        $viewData['title'] = 'User add a Car - Parkplatzverwaltung';
        $viewData['users'] = User::findOrFail(Auth::id())->where('deleted_at', null);

        return view('user.addCar.index')->with("viewData", $viewData);
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function show($id): Factory|View|Application
    {
        $viewData = [];
        $car = Car::findOrFail($id);
        $viewData['title'] = 'Reservierung: ' . $car->sign;
        $viewData['subtitle'] = 'Details von ' . $car->sign;
        $viewData['car'] = $car;
        $viewData['parking_spots'] = ParkingSpot::where('status', 'frei')->where('deleted_at', null)->get();

        return view('cars.show', [$id])->with('viewData', $viewData);
    }

    /**
     * @param CarRequest $request
     * @param CreateNewCar $createNewCar
     * @param SetImageName $setImageName
     * @return Redirector|Application|RedirectResponse
     */
    public function addCar(
        CarRequest    $request,
        CreateNewCar  $createNewCar,
        SetImageName  $setImageName,
        CreateMessage $createMessage,
    ): Redirector|Application|RedirectResponse
    {
        $car = $createNewCar->handle($request, $setImageName, $createMessage);
        return redirect('/user/' . $car->user_id);
    }
}
