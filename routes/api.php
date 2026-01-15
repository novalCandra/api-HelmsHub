<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowedController;
use App\Http\Controllers\HelmController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, "login"]);
        Route::post('/register', [AuthController::class, "register"]);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get("/profile", [AuthController::class, "profile"]);
            Route::post('/logout', [AuthController::class, "logout"]);
        });
    });


    Route::prefix('helments')->group(function () {
        Route::middleware(['auth:sanctum', 'role:admin,user,petugas'])->group(function () {
            Route::get('/', [HelmController::class, "index"]);
            Route::get('/{id}', [HelmController::class, "show"]);
        });
        Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
            Route::post('/', [HelmController::class, "store"]);
            Route::put('/{id}', [HelmController::class, "update"]);
            Route::delete('/{id}', [HelmController::class, "destroy"]);
        });
    });

    Route::prefix('borroed')->group(function () {
        Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
            Route::post('/', [BorrowedController::class, "store"]);
            Route::get('/{id}', [BorrowedController::class, "show"]);
        });

        Route::middleware(['auth:sanctum', 'role:admin,petugas'])->group(function () {
            Route::get('/', [BorrowedController::class, "index"]);
            Route::get('/{id}', [BorrowedController::class, "show"]);
        });
    });


    Route::prefix('transaction')->group(function () {
        Route::middleware('auth:sanctum', 'role:user')->group(function () {
            Route::get('/{id}', [TransactionController::class, "show"]);
            Route::post('/', [TransactionController::class, "store"]);
        });
        Route::middleware('auth:sanctum', 'role:admin,penjaga')->group(function () {
            Route::get('/', [TransactionController::class, "index"]);
        });
    });
});
