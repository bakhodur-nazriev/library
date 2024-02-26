<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::post('logout',  [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('books/search', [BookController::class, 'search']);

    Route::prefix('books')->group(function () {
        Route::get('', [BookController::class, 'index']);
        Route::post('', [BookController::class, 'store']);
        Route::patch('/{id}', [BookController::class, 'update'])
            ->where('id', '[0-9]+');
        Route::delete('/{id}', [BookController::class, 'destroy']);
        Route::get('/{id}', [BookController::class, 'showById']);

        Route::get('/download/{id}', [BookController::class, 'download']);


        Route::post('upload-pdf', [BookController::class, 'uploadPdf']);
    });

    Route::resource('users', 'UserController');

    Route::resource('roles', 'RoleController')->except(['show']);

    Route::resource('permissions', 'PermissionController')->except(['show']);

    Route::post('roles/{role}/permissions', 'RolePermissionController@store');
});
