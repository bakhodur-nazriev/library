<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PrivilegeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//auth
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
// auth
            Route::post('register', [AuthController::class, 'register']);

            Route::prefix('books')->group(function () {
                Route::post('', [BookController::class, 'store']);
                Route::patch('/{id}', [BookController::class, 'update'])
                    ->where('id', '[0-9]+');
                Route::delete('/{id}', [BookController::class, 'destroy']);
                //
                Route::post('/upload/{id}', [BookController::class, 'uploadPdf']);

                Route::post('/detach-from-book-author/{book_id}/{author_id}', [BookController::class, 'detachFromBookTheAuthor']);
                Route::post('/detach-from-author-book/{author_id}/{book_id}', [BookController::class, 'detachFromAuthorTheBook']);

            });


            Route::prefix('authors')->group(function () {
                Route::post('', [AuthorController::class, 'store']);
                Route::patch('/{id}', [AuthorController::class, 'update'])
                    ->where('id', '[0-9]+');
                Route::delete('/{id}', [AuthorController::class, 'destroy']);
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

                    Route::post('assign-role-to-user/{role_id}/{user_id}', [PrivilegeController::class, 'assignRoleToUser']);
                    Route::post('assign-permission-to-user/{permission_id}/{user_id}', [PrivilegeController::class, 'assignPermissionToUser']);
                    Route::post('assign-permission-to-role/{permission_id}/{role_id}', [PrivilegeController::class, 'assignPermissionToRole']);

                    //
                    Route::prefix('roles')
                        ->group(function () {
                            Route::get('', [PrivilegeController::class, 'getRoles']);
                            Route::post('', [PrivilegeController::class, 'storeRole']);
                            Route::put('', [PrivilegeController::class, 'updateRole']);
                            Route::delete('', [PrivilegeController::class, 'destroyRole']);
                        });
                    //
                    Route::prefix('permissions')
                        ->group(function () {
                            Route::get('', [PrivilegeController::class, 'getPermission']);
                            Route::post('', [PrivilegeController::class, 'storePermission']);
                            Route::put('', [PrivilegeController::class, 'updatePermission']);
                            Route::delete('', [PrivilegeController::class, 'destroyPermission']);
                        });
                });
        });

    // general routes
    Route::prefix('books')
        ->group(function () {
            Route::get('', [BookController::class, 'index']);
            Route::get('search/{search_key}', [BookController::class, 'search']);
            Route::get('/download/{id}', [BookController::class, 'download']);
            Route::get('/{id}', [BookController::class, 'showById']);
        });

    Route::prefix('authors')
        ->group(function () {
            Route::get('', [AuthorController::class, 'index']);
            Route::get('/{id}', [AuthorController::class, 'showById']);
            Route::get('search/{search_key}', [AuthorController::class, 'search']);

        });

});
