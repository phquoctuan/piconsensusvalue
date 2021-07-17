<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/blog', 'BlogController@index');
Route::resource('posts', 'PostsController');

Route::group(['prefix' => 'api/v1', 'middleware' => ['api', 'cors']], function(){
    Route::resource('posts', 'PostsController');
});