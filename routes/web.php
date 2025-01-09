<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarkerController;

// Routes for Marker operations
Route::get('/', [MarkerController::class, 'index'])->name('index');
Route::get('/markers/create', [MarkerController::class, 'create'])->name('markers.create');
Route::post('/markers', [MarkerController::class, 'store'])->name('markers.store');

// API route for fetching markers
Route::get('/api/markers', function () {
    return response()->json(App\Models\Marker::all());
});
