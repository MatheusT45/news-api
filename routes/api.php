<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

Route::post('news/admin', [AuthController::class,'adminAdd']);
Route::post('news/admin/login', [AuthController::class,'login']);
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('news/add', [AuthController::class,'newsAdd']);
});