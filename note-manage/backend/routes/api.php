<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LabelController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Password Reset
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::apiResource('notes', NoteController::class);
Route::post('/notes/{note}/toggle-pin', [NoteController::class, 'togglePin']);
Route::post('/notes/{note}/verify-password', [NoteController::class, 'verifyPassword']);

Route::apiResource('labels', LabelController::class);
