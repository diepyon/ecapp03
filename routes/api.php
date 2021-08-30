<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/stocks', [StockController::class, 'show']);
Route::get('/stock/author/{author_id}', [StockController::class, 'stocksByAuthorId']);

Route::post('/stocks/create', [StockController::class, 'create']);




