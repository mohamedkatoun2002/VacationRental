<?php

use GrahamCampbell\ResultType\Success;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');


Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');
Route::get('/services', [App\Http\Controllers\HomeController::class, 'services'])->name('services');
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');


Route::group(['prefix' => 'hotels'], function () {

    // Hotel
    Route::get('/rooms/{id}', [App\Http\Controllers\Hotels\HotelsController::class, 'rooms'])->name('hotel.rooms');
    Route::get('/rooms-details/{id}', [App\Http\Controllers\Hotels\HotelsController::class, 'roomDetails'])->name('hotel.rooms.details');
    Route::post('/rooms-booking/{id}', [App\Http\Controllers\Hotels\HotelsController::class, 'roomBooking'])->name('hotel.rooms.booking');

    //payment
    Route::get('/pay', [App\Http\Controllers\Hotels\HotelsController::class, 'payWithPaypal'])->name('hotel.pay')->middleware('check.for.price');
    Route::get('/success', [App\Http\Controllers\Hotels\HotelsController::class, 'success'])->name('hotel.success')->middleware('check.for.price');
});

//Users
Route::get('users/my-bookings', [App\Http\Controllers\Users\UsersController::class, 'myBookings'])->name('users.bookings')->middleware('auth:web');


//Admin Panel//
//Login
Route::get('admin/login', [App\Http\Controllers\Admins\AdminsController::class, 'viewLogin'])->name('view.login')->middleware('check.for.login');
Route::post('admin/login', [App\Http\Controllers\Admins\AdminsController::class, 'checkLogin'])->name('check.login')->middleware('check.for.login');

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin']], function () {
    //Dashboard
    Route::get('/index', [App\Http\Controllers\Admins\AdminsController::class, 'index'])->name('admins.dashboard');

    //Admins
    Route::get('/all-admins', [App\Http\Controllers\Admins\AdminsController::class, 'allAdmins'])->name('admins.all');
    Route::get('/create-admins', [App\Http\Controllers\Admins\AdminsController::class, 'createAdmins'])->name('admins.create');
    Route::post('/create-admins', [App\Http\Controllers\Admins\AdminsController::class, 'storeAdmins'])->name('admins.store');


    //hotels
    Route::get('/all-hotels', [App\Http\Controllers\Admins\AdminsController::class, 'allHotels'])->name('hotels.all');
    Route::get('/create-hotels', [App\Http\Controllers\Admins\AdminsController::class, 'createHotels'])->name('hotels.create');
    Route::post('/create-hotels', [App\Http\Controllers\Admins\AdminsController::class, 'storeHotels'])->name('hotels.store');
    Route::get('/edit-hotels/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'editHotels'])->name('hotels.edit');
    Route::post('/update-hotels/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'updateHotels'])->name('hotels.update');
    Route::get('/delete-hotels/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'deleteHotels'])->name('hotels.delete');

    //apartments
    Route::get('/all-rooms', [App\Http\Controllers\Admins\AdminsController::class, 'allRooms'])->name('rooms.all');
    Route::get('/create-rooms', [App\Http\Controllers\Admins\AdminsController::class, 'createRooms'])->name('rooms.create');
    Route::post('/create-rooms', [App\Http\Controllers\Admins\AdminsController::class, 'storeRooms'])->name('rooms.store');
    Route::get('/delete-rooms/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'deleteRooms'])->name('rooms.delete');


    //Bookings
    Route::get('/all-bookings', [App\Http\Controllers\Admins\AdminsController::class, 'allBookings'])->name('bookings.all');
    Route::get('/edit-status/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'editStatus'])->name('bookings.edit.status');
    Route::post('/update-status/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'updateStatus'])->name('bookings.update.status');
    Route::get('/delete-bookings/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'deleteBookings'])->name('bookings.delete');
});
