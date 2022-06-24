<?php

use App\Http\Controllers\LoginAdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return [
        'type' => get_class($request->user()),
        'user' => $request->user()
    ];
});

Route::prefix('auth')->group(function() {
    Route::post('/login', [LoginController::class, 'login'])->name('user.login');
    Route::post('/admin/login', [LoginAdminController::class, 'login'])->name('admin.login');

    Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout');
    Route::post('/admin/logout', [LoginAdminController::class, 'logout'])->name('admin.logout');
});

Route::resource('restaurants', RestaurantController::class)->except('create', 'edit');
