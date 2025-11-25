<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Token protected routes
Route::group(['middleware' => ['auth:api']], function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

// Book routes (JSON only)
// Public list & show (optional public)
Route::get('books', [BookController::class, 'index']);
Route::get('books/{book}', [BookController::class, 'show']);

// Librarian-only create/update/delete
Route::group(['middleware' => ['auth:api', 'role:librarian']], function() {
    Route::post('books', [BookController::class, 'store']);
    Route::put('books/{book}', [BookController::class, 'update']);
    Route::patch('books/{book}', [BookController::class, 'update']);
    Route::delete('books/{book}', [BookController::class, 'destroy']);
});

// Borrow/return (requires auth, users can borrow, librarians maybe manage)
Route::post('books/{book}/borrow', [BookController::class, 'borrow'])->middleware('auth:api');
Route::post('books/{book}/return', [BookController::class, 'return'])->middleware('auth:api');
