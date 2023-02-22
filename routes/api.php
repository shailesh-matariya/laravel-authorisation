<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/index', [RoleController::class, 'index'])
            ->name('index')
            ->can('viewAny', Role::class);

        Route::post('/store', [RoleController::class, 'store'])
            ->name('store')
            ->can('create', Role::class);

        Route::get('/show/{role}', [RoleController::class, 'show'])
            ->name('show')
            ->can('view', 'role');

        Route::put('/update/{role}', [RoleController::class, 'update'])
            ->name('update')
            ->can('update', 'role');

        Route::delete('/destroy/{role}', [RoleController::class, 'destroy'])
            ->name('destroy')
            ->can('delete', 'role');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/index', [UserController::class, 'index'])
            ->name('index')
            ->can('viewAny', User::class);

        Route::post('/store', [UserController::class, 'store'])
            ->name('store')
            ->can('create', User::class);

        Route::get('/show/{user}', [UserController::class, 'show'])
            ->name('show')
            ->can('view', 'user');

        Route::put('/update/{user}', [UserController::class, 'update'])
            ->name('update')
            ->can('update', 'user');

        Route::delete('/destroy/{user}', [UserController::class, 'destroy'])
            ->name('destroy')
            ->can('delete', 'user');

        Route::post('/assign/roles', [UserController::class, 'assignRoles'])
            ->name('assign-roles')
            ->can('assignRole', User::class);
    });
});
