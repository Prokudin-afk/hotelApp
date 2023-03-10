<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;

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

Route::get('/', function () {
    return view('home');
});

Route::get('/control_panel', function () {
    return ((session('role') == 'operator')?view('control_panel'):false);
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/log_in', [UserController::class, 'log_in']);
Route::post('/log_out', [UserController::class, 'log_out']);

Route::post('/search_rooms', [BookingController::class, 'search_rooms']);
Route::post('/make_booking', [BookingController::class, 'make_booking']);
Route::post('/delete_booking', [BookingController::class, 'delete_booking']);
Route::post('/show_orders', [BookingController::class, 'show_orders']);
Route::post('/load_table', [BookingController::class, 'load_table']);

