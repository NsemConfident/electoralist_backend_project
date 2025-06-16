<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiometricController;
use App\Http\Controllers\PresidentialCandidateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\VoterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('presidential-candidates', [PresidentialCandidateController::class, 'store']);
});
Route::apiResource('users', UserController::class);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::get('/candidates', [PresidentialCandidateController::class, 'index']);
        // Voter routes
        Route::get('/status', [VoterController::class, 'status']);
        Route::post('/voter/register', [VoterController::class, 'register']);
        Route::post('voter/vote', [VoterController::class, 'vote']);
    });

    // Add other protected routes here
    // Route::apiResource('posts', PostController::class);
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
