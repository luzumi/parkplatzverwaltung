<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'App\Http\Controllers\HomeController@index')->name("home.index");
Route::get('/about', 'App\Http\Controllers\HomeController@about')->name("home.about");

Route::get('/cars', 'App\Http\Controllers\CarController@index')->name("car.index");
Route::get('/car/{id}', 'App\Http\Controllers\CarController@show')->name("cars.show");

Route::get('/parking_spots', 'App\Http\Controllers\ParkingSpotController@index')->name("parking_spot.index");
Route::get('/parking_spot/{id}', 'App\Http\Controllers\ParkingSptController@show')->name("parking_spots.show");
