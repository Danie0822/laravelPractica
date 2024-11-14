<?php
// routes/profesores.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfesorController;

Route::get('/profesores', [ProfesorController::class, 'index']);
Route::post('/profesores', [ProfesorController::class, 'store']);
Route::get('/profesores/{id}', [ProfesorController::class, 'show']);
Route::delete('/profesores/{id}', [ProfesorController::class, 'destroy']);
Route::put('/profesores', [ProfesorController::class, 'update']);
Route::post('/profesores/login', [ProfesorController::class, 'login']);