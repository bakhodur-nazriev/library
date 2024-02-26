<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

//auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')
    ->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });

//
Route::middleware(['auth:sanctum'])->group(function () {
    //admin
    Route::middleware(['can:admin'])
        ->prefix('admin')
        ->group(function () {
            Route::prefix('books')->group(function () {
                Route::get('', [BookController::class, 'index']);
                Route::post('', [BookController::class, 'store']);
                Route::patch('/{id}', [BookController::class, 'update'])
                    ->where('id', '[0-9]+');
                Route::delete('/{id}', [BookController::class, 'destroy']);
                Route::get('/{id}', [BookController::class, 'showById']);
            });

            Route::prefix('users')
                ->group(function () {
                    Route::resource('users', 'UserController');
                    Route::resource('roles', 'RoleController');
                    Route::resource('permissions', 'PermissionController');
                    Route::post('roles/{role}/permissions', 'RolePermissionController@store');
                });
        });

    //users
    Route::prefix('books')
        ->group(function () {
            Route::get('', [BookController::class, 'index']);
            Route::get('search/{search_key}', [BookController::class, 'search']);
            Route::get('/download/{id}', [BookController::class, 'download']);
    });

});
