<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Enums\RoleEnum;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\SupplierController;

Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth')->group(function () {

    Route::get('test', [AuthController::class, 'test'])->middleware('role:' . implode(',', [RoleEnum::ADMIN->value]));

    Route::get('/roles', [CommonController::class, 'getRoles'])->middleware('role:' . implode(',', [RoleEnum::ADMIN->value]));
    Route::post('update-password', [AuthController::class, 'updatePassword']);

    Route::prefix('staff')->group(function () {
        Route::get('/', [StaffController::class, 'index']);
        Route::post('/create', [StaffController::class, 'store']);
        Route::get('/{id}', [StaffController::class, 'show']);
        Route::put('/update/{id}', [StaffController::class, 'update']);
        Route::delete('/delete/{id}', [StaffController::class, 'destroy']);
    })->middleware('role:' . implode(',', [RoleEnum::ADMIN->value]));

    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/create', [SupplierController::class, 'store']);
        Route::get('/{id}', [SupplierController::class, 'show']);
        Route::put('/update/{id}', [SupplierController::class, 'update']);
        Route::delete('/delete/{id}', [SupplierController::class, 'destroy']);
    })->middleware('role:' . implode(',', [RoleEnum::ADMIN->value]));
});
