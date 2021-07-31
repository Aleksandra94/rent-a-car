<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Models\Car;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Models\Category;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/car', [CarController::class, 'list']);

Route::post('/car', [CarController::class, 'store']);

Route::put('car/{car}', [CarController::class, 'update']);

Route::delete('car/{car}', [CarController::class, 'destroy']);
    
Route::get('/car/{car:slug}', [CarController::class, 'getSlug']);
   
Route::get('search', [CarController::class, 'search']);

Route::post('reservation', [CarController::class, 'reservation']);

Route::get('/category', [CategoryController::class, 'list']);

Route::post('/category', [CategoryController::class, 'store']);

Route::put('category/{category}', [CategoryController::class, 'update']);

Route::delete('category/{category}', [CategoryController::class, 'destroy']);
  
Route::post('/currency', [CurrencyController::class, 'exchangeCurrency']);