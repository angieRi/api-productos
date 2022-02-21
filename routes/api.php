<?php

use App\Http\Controllers\Api\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('product/create',[ProductController::class,'create']);
Route::get('product/getAll',[ProductController::class,'getAll']);
Route::get('product/getById/{id}',[ProductController::class,'getById']);
Route::post('product/edit/{producto}',[ProductController::class,'edit']);
Route::delete('product/delete/{id}',[ProductController::class,'delete']);
Route::get('product/search',[ProductController::class,'search']);
