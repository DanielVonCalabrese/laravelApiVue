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

// List Articles
Route::get('articles', 'ArticleController@index');

// List single Article
Route::get('article/{id}', 'ArticleController@show');

// Create new Article
Route::post('article/store', 'ArticleController@store');

// Update Article
Route::put('article/update', 'ArticleController@store');

// Delete Article
Route::delete('article/{id}', 'ArticleController@destroy');
