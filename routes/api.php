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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// List articles
Route::get('blogs', 'BlogController@index');

// List single article
Route::get('blog/{id}', 'BlogController@show');

// Create new article
Route::post('blog', 'BlogController@store');

// Update article
Route::put('blog', 'BlogController@store');

// Delete article
Route::Delete('blog/{id}', 'BlogController@destroy');