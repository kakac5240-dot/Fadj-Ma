<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineGroupController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1');
Route::post('/auth/resend-otp', [AuthController::class, 'resendOtp'])->middleware('throttle:5,1');
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1');
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:10,1');

Route::get('/medicines', [MedicineController::class, 'index']);
Route::get('/medicines/{id}', [MedicineController::class, 'show']);
Route::put('/medicines/{id}', [MedicineController::class, 'update']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/medicines', [MedicineController::class, 'store']);
    Route::delete('/medicines/{id}', [MedicineController::class, 'destroy']);
});

Route::apiResource('groups', MedicineGroupController::class)->except(['show']);
Route::get('/groups/{id}', [MedicineGroupController::class, 'show']);

Route::apiResource('clients', ClientController::class)->except(['show']);
Route::apiResource('suppliers', SupplierController::class)->except(['show']);

Route::get('/sales', [SaleController::class, 'index']);
Route::post('/sales', [SaleController::class, 'store']);

Route::get('/dashboard', [DashboardController::class, 'index']);