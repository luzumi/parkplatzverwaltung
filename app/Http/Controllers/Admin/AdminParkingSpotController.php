<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\AdminCreateNewParkingSpot;
use App\Actions\Admin\AdminUpdateParkingSpot;
use App\Actions\SetImageName;
use App\Http\Controllers\Controller;
use App\Http\Requests\ParkingSpotRequest;
use App\Models\Car;
use App\Models\ParkingSpot;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminParkingSpotController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $viewData = [];
        $viewData['title'] = 'Admin-Panel - Parkplatzübersicht - Parkplatzverwaltung';
        $viewData['parking_spots'] = ParkingSpot::getAllParkingSpotsWithCars();
        $viewData['cars'] = Car::with('parkingSpot')->get();

        return view('admin.parking_spot.index')->with("viewData", $viewData);
    }

    /**
     * @param ParkingSpotRequest $request
     * @param AdminCreateNewParkingSpot $createNewParkingSpot
     * @return RedirectResponse
     */
    public function storeNewParkingSpot(ParkingSpotRequest $request, AdminCreateNewParkingSpot $createNewParkingSpot): RedirectResponse
    {
        $createNewParkingSpot->handle($request);

        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        ParkingSpot::destroy($id);
        return back();
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): Factory|View|Application
    {
        $viewData = [];
        $viewData['title'] = 'Admin-Page - Editiere Fahrzeug - Parkplatzverwaltung';
        $viewData['parking_spot'] = ParkingSpot::findOrFail($id);

        return view('admin.parking_spot.edit')->with('viewData', $viewData);

    }

    /**
     * @param ParkingSpotRequest $request
     * @param SetImageName $setImageName
     * @param int $car_id
     * @param AdminUpdateParkingSpot $updateParkingSpot
     * @return RedirectResponse
     */
    public function update(ParkingSpotRequest $request, SetImageName $setImageName, int $car_id, AdminUpdateParkingSpot $updateParkingSpot): RedirectResponse
    {
        $updateParkingSpot->handle($request, $setImageName, $car_id);

        return redirect()->route('admin.parking_spot.index');
    }
}
