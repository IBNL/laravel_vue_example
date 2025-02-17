<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\UserController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/


Route::get('/', function () {
    return response()->json([
        'code' => 200,
        'message' => 'Laravel with Vue Example',
    ], 200);
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::prefix('v1')->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('/getDataFromFile', [ProductController::class, 'getDataFromFile']);
    });
});

Route::post('/login', [UserController::class, 'index']);
