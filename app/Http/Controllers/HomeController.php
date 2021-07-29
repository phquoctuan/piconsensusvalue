<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SweetAlert;
use Illuminate\Support\Facades\Cache;


class HomeController extends Controller
{
    //
    public function index()
    {
        alert()->message('Hello', 'Pi coin consensus value')->persistent('Close');
        // alert()->warning('Message', 'Optional Title');
        // alert()->message('Message', 'Optional Title');
        // alert()->basic('Basic Message', 'Mandatory Title');
        // alert()->info('Info Message', 'Optional Title');
        // alert()->success('Success Message', 'Optional Title');
        // alert()->error('Error Message', 'Optional Title');
        // alert()->warning('Warning Message', 'Optional Title');
        // alert()->basic('Basic Message', 'Mandatory Title')->autoclose(3500);
        // alert()->success('Your product has been updated', 'Thank you')->persistent('Close');
        //alert()->error('Error Message', 'Optional Title')->persistent('Close');
        //Alert::info('Welcome to our website', 'Hi! This is a Sweet Alert message!');
        //SweetAlert::message('Robots are working!');
        $pival = 0;
        if (Cache::has('CurrentPiValue')){
            $cacheValue = Cache::get('CurrentPiValue');
            $pival = $cacheValue["current_value"];
        }
        else{
            $responsedata = app('App\Http\Controllers\ProposalController')->currentValue();
            $content = $responsedata->getContent();
            $array = json_decode($content, true);
            //dd(($responsedata));
            $pival = $array['current_value'];
        }
        return view('home')->with('current_value', number_format($pival,7));
    }
}
