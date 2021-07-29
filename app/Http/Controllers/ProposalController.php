<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Proposal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Exception;
//use Response;
use Illuminate\Support\Str;
use Debugger;
// use App\Classes\CurrentPiValue;
use App\Classes\Contracts\CurrentValueInterface;
use Illuminate\Support\Facades\Cache;
use App\PiValueLog;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    public function currentValue()
    {
        if (!Cache::has('CurrentPiValue')) {
            //calculate current value
            //1: get lasttest PiValueLog
            $lastlog = PiValueLog::orderBy('propose_time', 'desc')->first();//PiValueLog::latest();
            if ($lastlog){
                $new_last_log = false;
                $new_proposals = Proposal::select(DB::raw("COUNT(*) AS total_propose, SUM(propose) AS sum_propose , MAX(created_at) AS propose_time"))
                ->where('created_at', '>',  $lastlog->propose_time)
                ->first();
                if($new_proposals->total_propose > 0){
                    $new_last_log = true;
                    $new_total_propose = $lastlog->total_propose + $new_proposals->total_propose;
                    $new_pivalue = (($lastlog->current_value * $lastlog->total_propose) + $new_proposals->sum_propose)/$new_total_propose;
                    $new_propose_time = $new_proposals->propose_time;
                }
                else{ //no new proposal data
                    $new_total_propose = $lastlog->total_propose;
                    $new_pivalue = $lastlog->current_value;
                    $new_propose_time = $lastlog-> propose_time;
                }
                //save new PiValueLog
                $newdata = array(
                    'current_value' => $new_pivalue,
                    'total_propose' => $new_total_propose,
                    'propose_time' => $new_propose_time
                );

                if($new_last_log){
                    $CurrentPiValue = new PiValueLog($newdata);
                    $CurrentPiValue->save();
                }
                //save cache
                $newdata["lastlog_time"] = $new_propose_time;
                Cache::put('CurrentPiValue', $newdata);

            }
            else{//no last log in database
                //calculate everage value base all proposal
                $new_proposals = Proposal::select(DB::raw("COUNT(*) AS total_propose, SUM(propose) AS sum_propose , MAX(created_at) AS propose_time"))
                ->first();

                if($new_proposals->total_propose > 0){
                    //dd($new_proposals->total_propose);
                    $new_total_propose = $new_proposals->total_propose;
                    $new_pivalue = $new_proposals->sum_propose/$new_proposals->total_propose;
                    $new_propose_time = $new_proposals->propose_time;
                }
                else{// no data at all
                    $new_total_propose = 0;
                    $new_pivalue = 0;
                    $new_propose_time = null;
                }
                //save new PiValueLog
                $newdata = array(
                    'current_value' => $new_pivalue,
                    'total_propose' => $new_total_propose,
                    'propose_time' => $new_propose_time
                );
                $CurrentPiValue = new PiValueLog($newdata);
                if($CurrentPiValue->propose_time != null)
                {
                    $CurrentPiValue->save();
                }
                $newdata["lastlog_time"] = $new_propose_time;
                Cache::put('CurrentPiValue', $newdata);
            }
        }

        $cacheValue = Cache::get('CurrentPiValue');
        lad($cacheValue);
        $response = response()->json(['current_value' => $cacheValue["current_value"] ], 200);
        return $response;
    }

    public function _currentValue(CurrentValueInterface $currentvalue)
    {
        // $randomId = rand(2,50);
        // $randomId = $currentvalue->CurrentPiValue;
        // $currentvalue->setNewPiValue($randomId + 1);

        $randomId = config()->get('currentvalue.CurrentPiValue');
        $newVal = $randomId + 1;
        // config(['currentvalue.CurrentPiValue', $newVal]);
        config()->set('currentvalue.CurrentPiValue', $newVal);
        lad(config('currentvalue.CurrentPiValue'));
        $response = response()->json(['current_value' => $randomId], 200);
        return $response;
        //response()->json(['data' => $posts]);

    }

    public function create(Request $request)
    {
        //validate data
        if ((!$request->propose) || (!$request->publickey)) {
            if((!$request->propose)){
                $message = ['message' => 'Please enter proposal value !'
                            , "errors" => ["required fields: propose, publickey"]];
            }
            else {
                $message = ['message' => 'Please enter all required fields'
                            , "errors" => ["required fields: propose, publickey"]];
            }
            lad($request->publickey);
            lad($request->propose);
            $response = response()->json($message, 402);
            return $response;
        }
        $validator = Validator::make($request->all(), [
            'propose' => 'required|numeric|min:0',
            'current' => 'required|numeric|min:0',
            'publickey' => 'required|alphaNum',
        ]);
        if ($validator->fails()) {
            $message = ['message' => 'Input data invalid'
                    ,   'errors' => $validator->errors()->all()];
            $response = response()->json($message, 202);
            return $response;
        }

        //check fake data: deiffence between donate and real donate
        if (Cache::has('CurrentPiValue')) {
            $cacheValue = Cache::get('CurrentPiValue');
            $curpival = $cacheValue["current_value"];
        }
        else{
            $responsedata = app('App\Http\Controllers\ProposalController')->currentValue();
            $content = $responsedata->getContent();
            $array = json_decode($content, true);
            $curpival = $array['current_value'];
        }

        if($curpival == 0){
            $realdonate = $request->propose / (10 * $request->propose);
        }
        else{
            $realdonate = abs($request->propose - $curpival)/(10 * $curpival);
        }
        lad($realdonate);
        lad($request->donate);
        if(abs($realdonate - $request->donate) > 0.01) {
            //fake data;
            $message = ['message' => 'It seems to be your data is out of date'
            , "errors" => ["Please refresh to update current Pi value."]];
            $response = response()->json($message, 402);
            return $response;
        }

        //save propose
        $proposal = new Proposal(array(
            'propose' => $request->propose,
            'current' => $request->current,
            'donate' => $request->donate,
            'public_key' => $request->publickey,
            'note' => $request->note,
        ));
        try
        {
            $proposal->save();
        }
        catch(Exception $e)
        {
            $message = ['message' => $e->getMessage()];
            $response = response()->json($message, 500);
            return $response;
        }
        //update cache
        if (Cache::has('CurrentPiValue')) {
            $cacheValue = Cache::get('CurrentPiValue');
            //calculate new pi value
            $total_propose = $cacheValue["total_propose"];
            $new_total_propose = $total_propose + 1;
            $new_pivalue = (($cacheValue["current_value"] * $total_propose) + $proposal->propose)/$new_total_propose;
            $new_propose_time = $proposal->created_at;
            $lastlog_time = $cacheValue["lastlog_time"];
            //$lastlog_time = strtotime($cacheValue["lastlog_time"]);
            //update cache or clear cache if last for long time
            if($lastlog_time != null) {
                $interval = $proposal->created_at->diff($lastlog_time);
                if($interval->i < 60){// interval = 60 minute
                    //update cache
                    $newdata = array(
                        'current_value' => $new_pivalue,
                        'total_propose' => $new_total_propose,
                        'propose_time' => $new_propose_time,
                        "lastlog_time" => $lastlog_time
                    );
                    Cache::forget('CurrentPiValue');
                    Cache::put('CurrentPiValue', $newdata);
                }
                else{
                    //clear cache and recalculate new value when request.
                    Cache::forget('CurrentPiValue');
                }
            }
            else{
                //no lastlog_time
                Cache::forget('CurrentPiValue');
            }
        }
        //Lanin debugger as a helper
        //lad($proposal);
        //Lanin debugger as a facade
        //Debugger::dump($proposal);

        $response = response()->json([
            'success' => 'OK',
            'message' => 'The proposal has been accepted, thank you for donation',
            'data' => $proposal,
        ], 201);
        return $response;
    }
}
