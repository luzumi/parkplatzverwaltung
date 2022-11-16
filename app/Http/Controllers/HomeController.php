<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $viewData = [];
        $viewData["title"] = "Home Page - Parkplatzverwaltung";
        $viewData['image'] = Auth::user()->image ?? '/storage/media/unregistered_user.png';
        return view('home.index')->with("viewData", $viewData);
    }

    public function about()
    {
        $viewData = [];
        $viewData['title'] = "About us - Parkplatzverwaltung";
        $viewData['subtitle'] = "About us";
        $viewData['description'] = "This is an about page ...";
        $viewData['author'] = "Developed by: luzumi";
        return view('home.about')->with("viewData", $viewData);
    }
}
