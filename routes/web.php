<?php

use Illuminate\Support\Facades\Auth;
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


Route::get('/', 'App\Http\Controllers\HomeController@index')
    ->name("home.index");

Route::get('/acceptMessage/{message_id}', 'App\Actions\AcceptMessage@acceptMessage')
    ->name("home.acceptMessage");

Route::get('/about', 'App\Http\Controllers\HomeController@about')
    ->name("home.about");

Route::get('/cars/', 'App\Http\Controllers\CarController@index')
    ->name("car.index");

Route::get('/car/{id}/', 'App\Http\Controllers\CarController@show')
    ->name("cars.show");

Route::get('/parking_spots/', 'App\Http\Controllers\ParkingSpotController@index')
    ->name("parking_spot.index");

Route::get('/parking_spot/{id}/', 'App\Http\Controllers\ParkingSpotController@show')
    ->name("parking_spots.show");

Route::post('/parking_spots/reserve/reserve/', 'App\Http\Controllers\ParkingSpotController@storeIndex')
    ->name("parking_spots.reserve_index");

Route::post('/parking_spots/reserve/store', 'App\Http\Controllers\ParkingSpotController@storeThisCar')
    ->name("parking_spots.storeThisCar");

Route::post('/parking_spots/reserve/store_reserve/{id}', 'App\Http\Controllers\ParkingSpotController@store')
    ->name("parking_spots.reserve.store_reserve");

Route::get('/parking_spots/reserve/store_cancel/{id}', 'App\Http\Controllers\ParkingSpotController@cancel')
    ->name("parking_spots.reserve.cancel");

Route::get('/user/', 'App\Http\Controllers\UserController@index')
    ->name("user.index");

Route::get('/user/{id}', 'App\Http\Controllers\UserController@show')
    ->name("user.show");

Route::get('user/editor/{id}', 'App\Http\Controllers\UserController@editor')
    ->name("user.editor-id");

Route::put('user/{id}/update', 'App\Services\UserService@update')
    ->name("user.update");

Route::put('user/{id}/updatePicture', 'App\Services\UserService@updatePicture')
    ->name("user.updatePicture");

Route::put('address/{id}/create', 'App\Http\Controllers\AddressController@create')
    ->name("address.create");

Route::delete('/user/delete/', 'App\Services\UserService@delete')
    ->name("user.delete");

Route::get('/user/addCar/index', 'App\Http\Controllers\CarController@storeIndex')
    ->name('user.addCar.index');

Route::post('/user/addCar/addCar', 'App\Http\Controllers\CarController@addCar')
    ->name('user.addCar.addCar');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    Route::get('/', 'App\Http\Controllers\Admin\AdminHomeController@index')
        ->name("admin.home.index");

    Route::get('/admin/cars', 'App\Http\Controllers\Admin\AdminCarController@index')
        ->name("admin.car.index");

    Route::get('/admin/users', 'App\Http\Controllers\Admin\AdminUserController@index')
        ->name("admin.user.index");

    Route::get('/admin/parking_spots', 'App\Http\Controllers\Admin\AdminParkingSpotController@index')
        ->name("admin.parking_spot.index");

    Route::post('/admin/cars/store', 'App\Http\Controllers\Admin\AdminCarController@store')
        ->name("admin.car.store");

    Route::post('/admin/users/store', 'App\Http\Controllers\Admin\AdminUserController@store')
        ->name("admin.user.store");

    Route::post(
        '/admin/parking_spots/store',
        'App\Http\Controllers\Admin\AdminParkingSpotController@storeNewParkingSpot'
    )->name("admin.parking_spot.store");

    Route::delete('/admin/cars/{id}/delete', 'App\Http\Controllers\Admin\AdminCarController@delete')
        ->name("admin.car.delete");

    Route::delete('/admin/users/{id}/delete', 'App\Http\Controllers\Admin\AdminUserController@delete')
        ->name("admin.user.delete");

    Route::delete('/admin/parking_spots/{id}/delete', 'App\Http\Controllers\Admin\AdminParkingSpotController@delete')
        ->name("admin.parking_spot.delete");

    Route::get('/admin/cars/{id}/edit', 'App\Http\Controllers\Admin\AdminCarController@edit')
        ->name("admin.car.edit");

    Route::get('/admin/users/{id}/edit', 'App\Http\Controllers\Admin\AdminUserController@edit')
        ->name("admin.user.edit");

    Route::get('/admin/parking_spots/{id}/edit', 'App\Http\Controllers\Admin\AdminParkingSpotController@edit')
        ->name("admin.parking-spot.edit");

    Route::put('/admin/cars/{id}/update', 'App\Http\Controllers\Admin\AdminCarController@update')
        ->name("admin.car.update");

    Route::put('/admin/users/{id}/update', 'App\Http\Controllers\Admin\AdminUserController@update')
        ->name("admin.user.update");

    Route::put('/admin/parking_spots/{id}/update', 'App\Http\Controllers\Admin\AdminParkingSpotController@update')
        ->name("admin.parking-spot.update");

//    Route::get('/user', 'App\Http\Controllers\UserController@index')->name("user.index");
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/admin', 'Admin\AdminHomeController@index');
});

Route::group(['prefix' => 'messages'], function () {
    Route::get('/', ['as' => 'messages', 'uses' => 'App\Http\Controllers\MessagesController@index']);
    Route::get('create', ['as' => 'messages.create', 'uses' => 'App\Http\Controllers\MessagesController@create']);
    Route::post('/', ['as' => 'messages.store', 'uses' => 'App\Http\Controllers\MessagesController@store']);
    Route::get('{id}', ['as' => 'messages.show', 'uses' => 'App\Http\Controllers\MessagesController@show']);
    Route::put('{id}', ['as' => 'messages.update', 'uses' => 'App\Http\Controllers\MessagesController@update']);
});

Route::group(['prefix' => 'admin/messages'], function () {
    Route::get('/', ['as' => 'admin.messages', 'uses' => 'App\Http\Controllers\Admin\AdminMessagesController@index']);
    Route::get('create', ['as' => 'admin.messages.create',
        'uses' => 'App\Http\Controllers\Admin\AdminMessagesController@create']);
    Route::post('/', ['as' => 'admin.messages.store',
        'uses' => 'App\Http\Controllers\Admin\AdminMessagesController@store']);
    Route::get('{id}', ['as' => 'admin.messages.show',
        'uses' => 'App\Http\Controllers\Admin\AdminMessagesController@show']);
    Route::put('update/{id}', ['as' => 'admin.messages.update',
        'uses' => 'App\Http\Controllers\Admin\AdminMessagesController@update']);
});

Route::post('/user/change-password', 'App\Http\Controllers\HomeController@updatePassword')
    ->name('user.update-password');


Auth::routes();
