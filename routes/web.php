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
Route::get('/parking_spot/{id}', 'App\Http\Controllers\ParkingSpotController@show')->name("parking_spots.show");

Route::get('/user', 'App\Http\Controllers\UserController@index')->name("user.index");
Route::get('/user/{id}', 'App\Http\Controllers\UserController@show')->name("user.show");

Route::get('/admin', 'App\Http\Controllers\Admin\AdminHomeController@index')->name("admin.home.index");
Route::get('/admin/cars', 'App\Http\Controllers\Admin\AdminCarController@index')->name("admin.car.index");
Route::get('/admin/users', 'App\Http\Controllers\Admin\AdminUserController@index')->name("admin.user.index");
Route::get('/admin/parking_spots', 'App\Http\Controllers\Admin\AdminParkingSpotController@index')->name("admin.parking_spot.index");

Route::post('/admin/cars/store', 'App\Http\Controllers\Admin\AdminCarController@store')->name("admin.car.store");
Route::post('/admin/users/store', 'App\Http\Controllers\Admin\AdminUserController@store')->name("admin.user.store");
Route::post('/admin/parking_spots/store', 'App\Http\Controllers\Admin\AdminParkingSpotController@store')->name("admin.parking_spot.store");

Route::delete('/admin/cars/{id}/delete', 'App\Http\Controllers\Admin\AdminCarController@delete')->name("admin.car.delete");
Route::delete('/admin/users/{id}/delete', 'App\Http\Controllers\Admin\AdminUserController@delete')->name("admin.user.delete");
Route::delete('/admin/parking_spots/{id}/delete', 'App\Http\Controllers\Admin\AdminParkingSpotController@delete')->name("admin.parking_spot.delete");

Route::get('/admin/cars/{id}/edit', 'App\Http\Controllers\Admin\AdminCarController@edit')->name("admin.car.edit");
Route::get('/admin/users/{id}/edit', 'App\Http\Controllers\Admin\AdminUserController@edit')->name("admin.user.edit");
Route::get('/admin/parking_spots/{id}/edit', 'App\Http\Controllers\Admin\AdminParkingSpotController@edit')->name("admin.parking-spot.edit");

Route::put('/admin/cars/{id}/update', 'App\Http\Controllers\Admin\AdminCarController@update')->name("admin.car.update");
Route::put('/admin/users/{id}/update', 'App\Http\Controllers\Admin\AdminUserController@update')->name("admin.user.update");
Route::put('/admin/parking_spots/{id}/update', 'App\Http\Controllers\Admin\AdminParkingSpotController@update')->name("admin.parking-spot.update");
