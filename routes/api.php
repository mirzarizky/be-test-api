<?php

use Illuminate\Http\Request;

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

Route::prefix('auth')->group(function () {
    Route::post('/', 'AuthController@login');

    Route::middleware(['auth:sanctum', 'scope:app'])->group(function () {
        Route::post('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::middleware(['auth:sanctum', 'scope:app'])->group(function () {
    Route::resource('product', 'ProductController')->except(['create', 'edit']);
});

Route::fallback(function () {
    return response()->json([
        'status_message' => 'not found'
    ], 404);
})->name('api.fallback.404');
