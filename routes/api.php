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

Route::get('proposal/current', 'ProposalController@currentValue');
Route::post('/proposal/serverapproval', 'ProposalController@ApprovalPayment');
Route::post('/proposal/servercompletion', 'ProposalController@CompletionPayment');
Route::post('/proposal/cancel', 'ProposalController@CancelPayment');
Route::post('/proposal/error', 'ProposalController@ErrorPayment');
Route::post('/proposal/incomplete', 'ProposalController@InCompletionPayment');
Route::post('/proposal/checkproposal', 'ProposalController@CheckProposal');

Route::post('/donatelog/saveluckydraw', 'DonateLogController@SaveLuckyDraw');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

