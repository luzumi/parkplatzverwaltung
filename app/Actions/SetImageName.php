<?php

namespace App\Actions;

use App\Http\Requests\CarRequest;
use App\Http\Requests\ParkingSpotRequest;
use App\Http\Requests\UserPictureRequest;
use App\Http\Requests\UserRequest;
use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\StorageLinker;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SetImageName
{
    /**
     * @param UserRequest|UserPictureRequest|CarRequest|ParkingSpotRequest $request
     * @param User $model
     * @return string|null
     */
    public function handle(
        UserRequest|UserPictureRequest|CarRequest|ParkingSpotRequest $request,
        Model                                                        $model
    ): string|null {
        if ($model instanceof User) {
            $model->image = $this->setUserImage($request, $model);
        }
        if ($model instanceof Car) {
            $model->image = $this->setCarImage($request, $model);
        }
        if ($model instanceof ParkingSpot) {
            $model->image = $this->setParkingSpotImage($model);
        }

        return $model->image;
    }

    private function setUserImage(UserRequest|UserPictureRequest $request, User $model)
    {
        if ($request->hasFile('image')) {
            $storageLinker = new StorageLinker([
                $model->name,
                $request->file('image')->extension()]);

            $imageName = $storageLinker['hash'];

            $this->storageOnDisk($imageName, $request);
        }

        return $imageName ?? $model->image;
    }

    private function setCarImage(CarRequest $request, Car $model)
    {
        if ($request->hasFile('image')) {
            $storageLinker = new StorageLinker([
                $model->sign,
                $request->file('image')->extension()]);

            $imageName = $storageLinker['hash'];

            $this->storageOnDisk($imageName, $request);
        }

        return $imageName ?? $model->image;
    }

    private function setParkingSpotImage(ParkingSpot $model)
    {
        $name = $model->status;
        $imageName = $name . '.jpg';
        $storageLinker = new StorageLinker([
            $imageName,
            $imageName,
        ]);


        Storage::disk('public/media')->put($name, '.jpg');

        return $imageName ?? $model->image;
    }


    /**
     * @param mixed $imageName
     * @param CarRequest|UserPictureRequest|UserRequest|ParkingSpotRequest $request
     * @return void
     */
    private function storageOnDisk(
        mixed                                                        $imageName,
        CarRequest|UserPictureRequest|UserRequest|ParkingSpotRequest $request
    ): void {
        Storage::disk('public/media')->put(
            $imageName,
            file_get_contents($request->file('image')->getRealPath())
        );
    }
}
