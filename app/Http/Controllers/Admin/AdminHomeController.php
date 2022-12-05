<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
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
//        $viewData['messages'] = Message::all()->sortbyDesc('messages');
        $viewData['messages'] = Message::all()->sortDesc();
//        dd($viewData);

        return view('admin.home.index')->with("viewData", $viewData);
    }
}
