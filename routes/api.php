<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\AttendeeController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('{org}')->group(function () {
    Route::get('/events', [PublicEventController::class, 'index']);
    Route::post('/register', [PublicEventController::class, 'register']);
});

// Admin routes (protected)
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::apiResource('events', EventController::class);
    Route::get('attendees', [AttendeeController::class, 'index']);
});

// Public routes
Route::prefix('{org}')->middleware('resolve.org')->group(function () {
    Route::get('events', [PublicEventController::class, 'index']);
    Route::post('register', [PublicEventController::class, 'register']);
});

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::post('logout', [AdminAuthController::class, 'logout']);
    });
});

Route::middleware('guest')->post('/register', [AuthController::class, 'register']);
Route::middleware('guest')->post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->prefix('admins')->group(function () {
    Route::get('/', [AdminController::class, 'index']);         // List admins in org
    Route::post('/', [AdminController::class, 'store']);        // Create admin
    Route::get('{id}', [AdminController::class, 'show']);       // View single admin
    Route::put('{id}', [AdminController::class, 'update']);     // Update admin
    Route::delete('{id}', [AdminController::class, 'destroy']); // Delete admin
});



