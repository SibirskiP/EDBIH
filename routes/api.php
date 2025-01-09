<?php

use App\Http\Controllers\InstrukcijaController;
use Illuminate\Http\Request;
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




//Route::middleware(['auth:sanctum'])->group(function () {
//    Route::get('/instrukcije', [InstrukcijaController::class, 'indexAPI']);
//    Route::get('/instrukcije/{instrukcija}', [InstrukcijaController::class, 'showAPI']);
//    Route::post('/instrukcije', [InstrukcijaController::class, 'storeAPI']);
//    Route::patch('/instrukcije/{instrukcija}', [InstrukcijaController::class, 'updateAPI']);
//    Route::delete('/instrukcije/{instrukcija}', [InstrukcijaController::class, 'destroyAPI']);
//});

Route::prefix('instrukcije')->group(function () {
    Route::get('/', [InstrukcijaController::class, 'indexAPI']); // Lista instrukcija
    Route::get('/{instrukcija}', [InstrukcijaController::class, 'showAPI']); // Prikaz pojedinačne instrukcije
    Route::post('/', [InstrukcijaController::class, 'storeAPI']); // Kreiranje instrukcije
    Route::put('/{instrukcija}', [InstrukcijaController::class, 'updateAPI']); // Ažuriranje instrukcije
    Route::delete('/{instrukcija}', [InstrukcijaController::class, 'destroyAPI']); // Brisanje instrukcije
});

Route::middleware('auth:sanctum')->get('/test', function (Request $request) {
    return response()->json(['message' => 'Authenticated!', 'user' => $request->user()]);
});


