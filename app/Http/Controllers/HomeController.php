<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Models\LogMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $viewData = [];
        $viewData["title"] = "Home Page - Parkplatzverwaltung";
        $viewData['image'] = Auth::user()->image ?? '/storage/media/unregistered_user.png';
        $viewData['messages'] = LogMessage::with('parkingSpot', 'car', 'user')
            ->where('receiver_user_id', '=', Auth::id())
            ->where('status', '=', 'pending')
            ->get();

        return view('home.index', compact("viewData"));
    }

    public function about()
    {
        $viewData = [];
        $viewData['title'] = "About us - Parkplatzverwaltung";
        $viewData['subtitle'] = "About us";
        $viewData['description'] = "";
        $viewData['author'] = "Developed by: luzumi";
        return view('home.about',compact("viewData"));
    }

    public function updatePassword(PasswordRequest $request)
    {
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return back()->with("error", "Old Password Doesn't match!");
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with("status", "Password changed successfully!");
    }
}
