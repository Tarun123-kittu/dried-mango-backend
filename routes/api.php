<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Enums\RoleEnum;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('test', [AuthController::class, 'test'])->middleware('role:' . implode(',', [RoleEnum::ADMIN->value]));;
});
