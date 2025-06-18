<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Tambahkan route GET untuk menampilkan halaman login & register
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');     // Diperlukan oleh middleware auth
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// POST routes untuk register, login, logout
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/', function () {
    return view('index');
});

// Route yang butuh login (auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
});
