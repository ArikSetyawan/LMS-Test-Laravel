<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpkoController; // Spko Controller
use App\Http\Controllers\SpkoitemController; // Spko_item Controller

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [SpkoController::class, 'index']); 
Route::post('/', [SpkoController::class, 'createSpko']);
Route::get('/delete_spko/{id_spko}',[SpkoController::class, 'deleteSpko']);
Route::get('/spko/{id_spko}', [SpkoController::class, 'detailSpko']);
Route::get('/spko_print/{id_spko}', [SpkoController::class, 'detailSpkoPrint']);
Route::post('/edit_spko/{id_spko}', [SpkoController::class, 'editSpko']);
Route::post('/edit_spkoitem/{id_spko}/{id}', [SpkoitemController::class, 'editSpkoItem']);