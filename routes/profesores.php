<?php
// routes/profesores.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfesorController;

Route::get('/profesores', [ProfesorController::class, 'index']);
Route::post('/profesores', [ProfesorController::class, 'store']);