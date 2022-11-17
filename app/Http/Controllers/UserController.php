<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index(): Factory|View|Application
    {
        $viewData = [];
        $viewData["title"] = "Parkplatzverwaltung";
        $viewData["subtitle"] = "User-Übersicht";
        $viewData["users"] = User::all();

        return view('user.index')->with("viewData", $viewData);
    }

    public function show(): Factory|View|Application
    {

        $viewData = [];
        $user = User::findOrFail(Auth::id());
        $viewData['user'] = $user;
        $viewData['cars'] = $user->cars;
        $viewData['title'] = $user['name'] . " - Parkplatzverwaltung";
        $viewData['subtitle'] = $user['name'] . " - User information";

        return view('user.show', [Auth::id()])->with("viewData", $viewData);
    }

    public function editor($id): Factory|View|Application
    {
        $viewData = [];
        $user = User::findOrFail($id);
        $viewData["user"] = $user;
        $viewData["title"] = $user["name"] . "Benutzerdaten editieren - Parkplatzverwaltung";
        $viewData["subtitle"] = $user["name"] . " - User editor";

        return view('user.editor-id', [$id])->with("viewData", $viewData);
    }
}
