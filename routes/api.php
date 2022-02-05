<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BookingController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::get('/categoryPackage', [BookingController::class,'categoryPackage']);
Route::resource('/booking', BookingController::class);
Route::post('/booking/patch', [BookingController::class,'patchBooking']);
Route::post('/booking/delete', [BookingController::class,'deleteBooking']);
Route::post('/booking/status', [BookingController::class,'statusUpdate']);


Route::resource('/category', CategoryController::class);
Route::resource('/package', PackageController::class);
Route::resource('/customers', CustomerController::class);
Route::post('/customers/delete', [CustomerController::class,'deleteCustomer']);
Route::post('/customers/patch', [CustomerController::class,'patchCustomer']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

