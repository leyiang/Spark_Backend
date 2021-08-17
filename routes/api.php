<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post("/image/fetch", [\App\Http\Controllers\ImageController::class, "fetch"]);
Route::post("/image/upload", [\App\Http\Controllers\ImageController::class, "upload"]);
Route::resource("image", \App\Http\Controllers\ImageController::class);
