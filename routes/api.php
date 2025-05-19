<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\AttendeeController;
use App\Http\Controllers\PublicEventController;

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
Route::prefix('{org}')->group(function () {
    Route::get('events', [PublicEventController::class, 'index']);
    Route::post('register', [PublicEventController::class, 'register']);
});

