<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarkerController;




Route::get('/recommendation', [MarkerController::class, 'index'])->name('index');

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/markers/create', [MarkerController::class, 'create'])->name('markers.create');
Route::post('/markers', [MarkerController::class, 'store'])->name('markers.store');


Route::get('/api/markers', function () {
    return response()->json(App\Models\Marker::all());
});



Route::get('/markers/{marker}/edit', [MarkerController::class, 'edit'])->name('markers.edit');
Route::put('/markers/{marker}', [MarkerController::class, 'update'])->name('markers.update');
Route::delete('/markers/{marker}', [MarkerController::class, 'destroy'])->name('markers.destroy');
