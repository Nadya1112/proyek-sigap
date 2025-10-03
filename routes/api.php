<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rute untuk mengambil data GeoJSON Kompleks
Route::get('/kompleks', [PetaController::class, 'kompleks']);

// Rute untuk mengambil data GeoJSON Kecamatan
Route::get('/kecamatan', [PetaController::class, 'kecamatan']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});