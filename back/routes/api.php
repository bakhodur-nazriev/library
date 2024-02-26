<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
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


            Route::prefix('authors')->group(function () {
                Route::get('', [AuthorController::class, 'index']);
                Route::post('', [AuthorController::class, 'store']);
                Route::patch('/{id}', [AuthorController::class, 'update'])
                    ->where('id', '[0-9]+');
                Route::delete('/{id}', [AuthorController::class, 'destroy']);
                Route::get('/{id}', [AuthorController::class, 'showById']);
            });

            Route::prefix('users')
                ->group(function () {
                    Route::get('', [UserController::class, 'index']);
                    Route::post('', [UserController::class, 'store']);
                    Route::put('', [UserController::class, 'update']);
                    Route::delete('', [UserController::class, 'destroy']);
                });

            Route::prefix('privileges')
                ->group(function () {

                    Route::post('assign-role-to-user/{role_id}/{user_id}', [UserController::class, 'assignRoleToUser']);
                    Route::post('assign-permission-to-user/{permission_id}/{user_id}', [UserController::class, 'assignPermissionToUser']);
                    Route::post('assign-permission-to-role/{permission_id}/{role_id}', [UserController::class, 'assignPermissionToRole']);

                    //
                    Route::prefix('roles')
                        ->group(function () {
                            Route::get('', [UserController::class, 'getRoles']);
                            Route::post('', [UserController::class, 'storeRole']);
                            Route::put('', [UserController::class, 'updateRole']);
                            Route::delete('', [UserController::class, 'destroyRole']);
                        });
                    //
                    Route::prefix('permissions')
                        ->group(function () {
                            Route::get('', [UserController::class, 'getPermission']);
                            Route::post('', [UserController::class, 'storePermission']);
                            Route::put('', [UserController::class, 'updatePermission']);
                            Route::delete('', [UserController::class, 'destroyPermission']);
                        });
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
