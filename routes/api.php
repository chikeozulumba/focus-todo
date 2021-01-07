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
            ['prefix' => 'todos'],
            function () {
                Route::get('/', [\App\Http\Controllers\TodosController::class, 'index']);
                Route::post('/', [\App\Http\Controllers\TodosController::class, 'store']);
                Route::get('/{todo}', [\App\Http\Controllers\TodosController::class, 'show']);
                Route::put('/{todo}', [\App\Http\Controllers\TodosController::class, 'update']);
                Route::delete('/{todo}', [\App\Http\Controllers\TodosController::class, 'destroy']);
                Route::patch('/{todo}/status/{status}', [\App\Http\Controllers\TodosController::class, 'status']);
                Route::patch('/{todo}/priority/{priority}', [\App\Http\Controllers\TodosController::class, 'priority'])
                    ->where('priority', '[0-9]+');
                Route::get('/{todo}/tasks/{task}', [\App\Http\Controllers\TasksController::class, 'task']);
            }
        );

        Route::group(
            ['prefix' => 'tasks'],
            function () {
                Route::get('/', [\App\Http\Controllers\TasksController::class, 'index']);
                Route::post('/', [\App\Http\Controllers\TasksController::class, 'store']);
                Route::get('/{task}', [\App\Http\Controllers\TasksController::class, 'show']);
                Route::get('/todo/{todo}', [\App\Http\Controllers\TasksController::class, 'showByTodo']);
                Route::put('/{task}', [\App\Http\Controllers\TasksController::class, 'update']);
                Route::delete('/{task}', [\App\Http\Controllers\TasksController::class, 'destroy']);
                Route::patch('/{task}/status/{status}', [\App\Http\Controllers\TasksController::class, 'status']);
                Route::post('/{task}/todo', [\App\Http\Controllers\TasksController::class, 'convertToTodo']);
            }
        );
    }
);
