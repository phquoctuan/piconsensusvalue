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

Route::get('proposal', 'ProposalController@index');
Route::post('proposal', 'ProposalController@index');
Route::get('proposal/current', 'ProposalController@currentValue');
// Route::post('proposal/propose', 'ProposalController@create');
Route::post('/proposal/serverapproval', 'ProposalController@ApprovalPayment');
Route::post('/proposal/servercompletion', 'ProposalController@CompletionPayment');
Route::post('/proposal/cancel', 'ProposalController@CancelPayment');
Route::post('/proposal/error', 'ProposalController@ErrorPayment');
Route::post('/proposal/incomplete', 'ProposalController@InCompletionPayment');
Route::post('/proposal/checkproposal', 'ProposalController@CheckProposal');
Route::get('/proposal/thismonthdonate', 'ProposalController@ThisMonthDonate');
Route::get('/proposal/lastmonthdonate', 'ProposalController@LastMonthDonate');

Route::get('drawhistory', 'DonateLogController@index');

Route::get('users/login', 'Auth\AuthController@getLogin');
Route::post('users/login', 'Auth\AuthController@postLogin');
Route::get('users/register', 'Auth\AuthController@getRegister');
Route::post('users/register', 'Auth\AuthController@postRegister');
