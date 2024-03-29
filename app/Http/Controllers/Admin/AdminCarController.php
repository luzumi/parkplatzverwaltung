<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\AdminUpdateCar;
use App\Actions\CreateMessage;
use App\Actions\CreateNewCar;
use App\Actions\SetImageName;
use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AdminCarController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $viewData = [];
        $viewData['title'] = 'Admin-Panel - Fahrzeugübersicht - Parkplatzverwaltung';
        $viewData['cars'] = Car::with('parkingSpot')->where('deleted_at', null)->get();

        return view('admin.car.index')->with("viewData", $viewData);
    }

    /**
     * @param CarRequest $request
     * @param CreateNewCar $createNewCar
     * @param SetImageName $setImageName
     * @param CreateMessage $createMessage
     * @return RedirectResponse
     */
    public function store(
        CarRequest $request,
        CreateNewCar $createNewCar,
        SetImageName $setImageName,
        CreateMessage $createMessage
    ): RedirectResponse {

        $createNewCar->handle($request, $setImageName, $createMessage);
        return back();
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function delete($car_id, CreateMessage $createMessage): RedirectResponse
    {
        $car = Car::findOrFail($car_id);
        $car->update([
            "deleted_at" => now(),
        ]);
        $createMessage->handle(MessageType::DeleteCar, $car->id, $car_id, null);

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
        $viewData['car'] = Car::findOrFail($id)->where('deleted_at', null)->first();

        return view('admin.car.edit')->with('viewData', $viewData);
    }


    /**
     * @param CarRequest $request
     * @param SetImageName $setImageName
     * @param int $car_id
     * @param AdminUpdateCar $updateCar
     * @param CreateMessage $createMessage
     * @return RedirectResponse
     */
    public function update(
        CarRequest $request,
        SetImageName $setImageName,
        int $car_id,
        AdminUpdateCar
        $updateCar,
        CreateMessage $createMessage
    ): RedirectResponse {

        $updateCar->handle($request, $setImageName, $car_id, $createMessage);

        return redirect()->route('admin.car.index');
    }
}
