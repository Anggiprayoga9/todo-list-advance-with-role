<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::get('/', function () {
    return view('welcome');
});

// Grup Route yang butuh Login
Route::middleware(['auth', 'verified'])->group(function () {

    // Ganti dashboard default Breeze ke TodoController
    Route::get('/dashboard', [TodoController::class, 'index'])->name('dashboard');

    // Route Baru: Halaman Progress
    Route::get('/progress', [TodoController::class, 'progress'])->name('todos.progress');
    
    // Todo Routes
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::patch('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');

    // Profile Routes (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';
