<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\LogMessage;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AdminHomeController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $viewData = [];
        $viewData['title'] = 'Adminpage - Home -Parkplatzverwaltung';
        $viewData['subtitle'] = '';
        $viewData['messages'] = LogMessage::with('user', 'car', 'parkingSpot')->get()->sortDesc();

        return view('admin.home.index')->with("viewData", $viewData);
    }
}
