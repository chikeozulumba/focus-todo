<?php

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

Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::group(
    [ 'middleware' => ['auth:sanctum'] ],
    function () {
        Route::group(
            ['prefix' => 'users'],
            function () {
                Route::post('/create', [\App\Http\Controllers\UsersController::class, 'create']);
            }
        );
    }
);
