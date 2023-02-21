<?php

use Illuminate\Http\Request;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\VoyageController;
use App\Http\Controllers\VesselOpexController;
use App\Http\Controllers\FinancialReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('vessel', [VesselController::class, 'index']);
Route::post('vessel', [VesselController::class, 'store']);
Route::put('vessel/{id}', [VesselController::class, 'update']);
Route::patch('vessel/{id}', [VesselController::class, 'update']);

Route::get('voyage', [VoyageController::class, 'index']);
Route::post('voyage', [VoyageController::class, 'store']);
Route::put('voyage/{id}', [VoyageController::class, 'update']);
Route::patch('voyage/{id}', [VoyageController::class, 'update']);

Route::post('/vessels/{id}/vessel-opex', [VesselOpexController::class, 'store']);

Route::get('/vessels/{id}/financial-report', [FinancialReportController::class, 'index']);