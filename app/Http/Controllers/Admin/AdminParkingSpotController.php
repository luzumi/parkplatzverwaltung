<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\AdminCreateNewParkingSpot;
use App\Actions\Admin\AdminUpdateParkingSpot;
use App\Actions\Admin\AdminCreateMessage;
use App\Actions\CreateMessage;
use App\Actions\SetImageName;
use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ParkingSpotRequest;
use App\Models\Car;
use App\Models\ParkingSpot;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AdminParkingSpotController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $viewData = [];
        $viewData['title'] = 'Admin-Panel - Parkplatzübersicht - Parkplatzverwaltung';
        $viewData['parking_spots'] = ParkingSpot::with('car')->where('deleted_at', '=', null)->get();
        $viewData['cars'] = Car::with('parkingSpot')->where('user_id', '!=', '1')->get();

        return view('admin.parking_spot.index')->with("viewData", $viewData);
    }

    /**
     * @param ParkingSpotRequest $request
     * @param AdminCreateNewParkingSpot $createNewParkingSpot
     * @param CreateMessage $createMessage
     * @return RedirectResponse
     */
    public function storeNewParkingSpot(
        ParkingSpotRequest        $request,
        AdminCreateNewParkingSpot $createNewParkingSpot,
        CreateMessage             $createMessage
    ): RedirectResponse {

        if (Auth::user()->hasRole('admin')){
            $createNewParkingSpot->handle($request, $createMessage);

            return back();
        }
        return abort(403,'Sie haben nicht die erforderlichen Berechtigungen, um diese Aktion durchzuführen.' );
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id, CreateMessage $createMessage): RedirectResponse
    {
        ParkingSpot::where('id', $id)
            ->update([
                'user_id' => '1',
                'image' => 'frei.jpg',
                'status' => 'frei',
                'deleted_at' => now(),
            ]);

        $createMessage->handle(MessageType::DeleteParkingSpot, Auth::id(), null, $id);
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
     * @param CreateMessage $createMessage
     * @return RedirectResponse
     */
    public function update(
        ParkingSpotRequest     $request,
        SetImageName           $setImageName,
        int                    $car_id,
        AdminUpdateParkingSpot $updateParkingSpot,
        AdminCreateMessage          $createMessage
    ): RedirectResponse {
        $updateParkingSpot->handle($request, $setImageName, $car_id, $createMessage);

        return redirect()->route('admin.parking_spot.index');
    }
}
