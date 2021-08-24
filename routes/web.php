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

Route::get('posts', 'PostsController@index');
Route::get('posts/create', 'PostsController@create');
Route::post('posts/create', 'PostsController@create');
Route::get('posts/edit/{id}', 'PostsController@edit');
Route::post('posts', 'PostsController@update')->middleware('checksavepass');

Route::get('lastalert', 'PostsController@AlertLastActivePost');
// Route::group(['prefix' => 'api/v1', 'middleware' => ['api', 'cors']], function(){
//     Route::resource('posts', 'PostsController');
// });

Route::get('/settings', 'SettingsController@index');
Route::get('/settings/edit/{id}', 'SettingsController@edit');
Route::post('/settings/update', 'SettingsController@update')->middleware('checksavepass');

Route::get('/proposal', 'ProposalController@index');
Route::post('/proposal', 'ProposalController@index');

Route::get('/proposal/thismonthdonate', 'ProposalController@ThisMonthDonate');
Route::get('/proposal/lastmonthdonate', 'ProposalController@LastMonthDonate');

Route::get('/drawhistory', 'DonateLogController@index');
Route::get('/luckydrawselect', 'DonateLogController@LuckyDrawSelect');
Route::post('/luckydrawresult', 'DonateLogController@LuckyDrawResult');
Route::get('/donatelog/getuserbyproposalid', 'DonateLogController@GetUserByProposalId');

Route::get('/about', 'CommonControler@about');

Route::get('users/login', 'Auth\AuthController@getLogin');
Route::post('users/login', 'Auth\AuthController@postLogin');
Route::get('users/register', 'Auth\AuthController@getRegister');
Route::post('users/register', 'Auth\AuthController@postRegister');
