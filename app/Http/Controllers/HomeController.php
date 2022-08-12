<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SweetAlert;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\Null_;
use \Datetime;
use App\Statictis;

class HomeController extends Controller
{
    //
    public function index()
    {
        // alert()->message('Hello', 'Pi coin consensus value')->persistent('Close');
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
        $statictises = statictis::orderBy('id')->get();
        $chartlabel = $statictises->pluck('label');
        $chartdata = $statictises->pluck('total');
        // var_dump($plucked);
        // die();

        $pival = 0;
        if (Cache::has('CurrentPiValue')){
            $CurrentPiValue = Cache::get('CurrentPiValue');
            $pival = $CurrentPiValue["current_value"];
        }
        else{
            $responsedata = app('App\Http\Controllers\ProposalController')->currentValue();
            $content = $responsedata->getContent();
            $CurrentPiValue = json_decode($content, true);
            // dd($CurrentPiValue);
            $pival = $CurrentPiValue['current_value'];
        }

        $ThisMonthDonate = null;
        if (Cache::has('LastDonateLog')){
            // lad("has this month");
            $ThisMonthDonate = Cache::get('LastDonateLog');
        }
        else{
            // lad("no this month");
            $responsedata = app('App\Http\Controllers\ProposalController')->ThisMonthDonate();
            $content = $responsedata->getContent();
            $ThisMonthDonate = json_decode($content, true);
        }
        //calculate remain second for this month
        $ThisdiffInSeconds = 0;
        if($ThisMonthDonate["fixed_drawdate"] == 1 && $ThisMonthDonate["draw_date"] != NULL){
            $todate = new DateTime($ThisMonthDonate["draw_date"]);
            $now = new DateTime();
            $diff = $now->diff($todate);
            $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
            $hoursInSecs = $diff->h * 60 * 60;
            $minsInSecs = $diff->i * 60;
            $ThisdiffInSeconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
            if ($ThisdiffInSeconds < 0) {$ThisdiffInSeconds = 0;}
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
        //calculate remain second for last month
        $LastdiffInSeconds = 0;
        if($LastMonthDonate["fixed_drawdate"] == 1 && $LastMonthDonate["draw_date"] != NULL){
            // $timeFirst  = strtotime('2011-05-12 18:20:20');
            // $timeSecond = strtotime('2011-05-13 18:20:20');
            //$date = new DateTime("2012-05-03 17:34:01");
            $todate = new DateTime($LastMonthDonate["draw_date"]);
            $now = new DateTime();
            $diff = $now->diff($todate);
            // $diffInSeconds = $todate - $now;
            $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
            $hoursInSecs = $diff->h * 60 * 60;
            $minsInSecs = $diff->i * 60;
            $LastdiffInSeconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
            if ($LastdiffInSeconds < 0) {$LastdiffInSeconds = 0;}
            // dd($diffInSeconds);
        }
        //check has post
        if($pival > 999999){
            $curpival = number_format($pival,2);
        }
        else if ($pival > 99999){
            $curpival = number_format($pival,3);
        }
        else if ($pival > 9999){
            $curpival = number_format($pival,4);
        }
        else{
            $curpival = number_format($pival,5);
        }

        //chart static data


        // header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
        return view('home')->with('current_value', $curpival)
                            ->with('current_pi_value', $CurrentPiValue)
                            ->with('this_month_donate', $ThisMonthDonate)
                            ->with('last_month_donate', $LastMonthDonate)
                            ->with('this_month_diff', $ThisdiffInSeconds)
                            ->with('last_month_diff', $LastdiffInSeconds)
                            ->with('chart_label', $chartlabel)
                            ->with('chart_data', $chartdata);
        // return view('home')->with('name', 'Victoria')->with('occupation', 'Astronaut');
        // return view('home', compact('var1','var2','var3'));
        // return $view->with('data', ['ms' => $ms, 'persons' => $persons])); -> {{ $data['ms'] }}
    }
}
