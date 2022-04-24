<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\Auth\RegisterController;//追記
use App\Http\Controllers\Api\Auth\LoginController;//追記

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

Route::get('/stocks', [StockController::class, 'show']);//StockControllerのshowメソッドを発動
Route::get('/image', [StockController::class, 'showImage']);//StockControllerのshowImageメソッド発動
//Route::get('/duration', [StockController::class, 'duration']);//選択されたビデオファイルの時間をlaravel-ffmpegで取得したい
Route::get('/stocks/{stock_id}', [StockController::class, 'single']);//urlのstock_idの部分に入力された数字をsingleメソッドに渡す

Route::get('/stock/author/{author_id}', [StockController::class, 'stocksByAuthorId']);

Route::get('/hoge/{author_id}', [UserController::class, 'index']);//idから投稿者情報を取得(作成中)

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/stocks/create', [StockController::class, 'create']);

//会員登録
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/loginCheck',function (Request $request) {
});

Route::post('/logout', [LoginController::class, 'logout']);