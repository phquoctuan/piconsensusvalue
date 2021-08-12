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
            $CurrentPiValue = Cache::get('CurrentPiValue');
            $pival = $CurrentPiValue["current_value"];
        }
        else{
            $responsedata = app('App\Http\Controllers\ProposalController')->currentValue();
            $content = $responsedata->getContent();
            $CurrentPiValue = json_decode($content, true);
            $pival = $CurrentPiValue['current_value'];
        }

        $ThisMonthDonate = null;
        if (Cache::has('LastDonateLog')){
            lad("has this month");
            $ThisMonthDonate = Cache::get('LastDonateLog');
        }
        else{
            lad("no this month");
            $responsedata = app('App\Http\Controllers\ProposalController')->ThisMonthDonate();
            $content = $responsedata->getContent();
            $ThisMonthDonate = json_decode($content, true);
        }

        $LastMonthDonate = null;
        if(Cache::has('LastMonthDonateLog')){
            lad("has last month");
            $LastMonthDonate = Cache::get('LastMonthDonateLog');
        }
        else{
            lad("no last month");
            $resdata = app('App\Http\Controllers\ProposalController')->LastMonthDonate();
            $cont = $resdata->getContent();
            $LastMonthDonate = json_decode($cont, true);
        }
        // header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
        return view('home')->with('current_value', number_format($pival,7))
                            ->with('current_pi_value', $CurrentPiValue)
                            ->with('this_month_donate', $ThisMonthDonate)
                            ->with('last_month_donate', $LastMonthDonate);
        // return view('home')->with('name', 'Victoria')->with('occupation', 'Astronaut');
        // return view('home', compact('var1','var2','var3'));
        // return $view->with('data', ['ms' => $ms, 'persons' => $persons])); -> {{ $data['ms'] }}
    }
}
