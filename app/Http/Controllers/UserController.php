<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Car;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $viewData = [];
        $viewData["title"] = "Parkplatzverwaltung";
        $viewData["subtitle"] = "User-Übersicht";
        $viewData["users"] = User::all()->where('deleted_at', null);

        return view('user.index')->with("viewData", $viewData);
    }

    /**
     * @param $user_id
     * @return Factory|View|Application
     */
    public function show($user_id): Factory|View|Application
    {
        $user = User::findOrFail($user_id)->where('deleted_at', null);

        $viewData = [];
        $viewData['user'] = $user;
        $viewData['cars'] = Car::with('parkingSpot')->where('cars.user_id', $user_id)->get();
        $viewData['address'] = Address::where('user_id', $user_id)->first();
        $viewData['title'] = $user['name'] . " - Parkplatzverwaltung";
        $viewData['subtitle'] = $user['name'] . " - User information";

        return view('user.show', [$user_id])->with("viewData", $viewData);
    }

    /**
     * @param $user_id
     * @return Factory|View|Application
     */
    public function editor($user_id): Factory|View|Application
    {
        $user = User::findOrFail($user_id)->where('deleted_at', null);

        $viewData = [];
        $viewData["subtitle"] = $user["name"] . " - User editor";
        $viewData['address'] = Address::where('user_id', $user_id)->first();
        $viewData["user"] = $user;
        $viewData["title"] = $user["name"] . "Benutzerdaten editieren - Parkplatzverwaltung";

        return view('user.editor-id', [$user_id])->with("viewData", $viewData);

    }
}
