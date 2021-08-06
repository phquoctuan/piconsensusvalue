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
use GuzzleHttp;

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
                ->where('completed','1')
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
                ->where('completed','1')
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

    /*
    //
    onReadyForServerApproval: (paymentId: string) => void,
    //
    */
    public function ApprovalPayment(Request $request){
        //validate data
        if ((!$request->propose) || (!$request->donate) || (!$request->paymentid)) {
            if((!$request->propose)){
                $message = ['message' => 'Please enter proposal value !'
                            , "errors" => ["required fields: propose, donate"]];
            }
            else
            if(!$request->donate) {
                $message = ['message' => 'Donate value invalid !'
                            , "errors" => ["required fields: propose, donate"]];
            }
            else{
                $message = ['message' => 'PaymentId is empty !'
                            , "errors" => ["required fields: propose, donate"]];
            }
            $response = response()->json($message, 402);
            return $response;
        }
        $validator = Validator::make($request->all(), [
            'propose' => 'required|numeric|min:0',
            'current' => 'required|numeric|min:0',
            'donate' => 'required|numeric|min:0',
            'username' => 'required|alphaNum',
            'paymentid' => 'required|alphaNum',
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
        if(abs($realdonate - $request->donate) > 0.01) {
            //fake data;
            $message = ['message' => 'It seems to be your data is out of date'
            , "errors" => ["Please refresh to update current Pi value."]];
            $response = response()->json($message, 402);
            return $response;
        }
        //register payment to database
        $ipAddr = $request->ip();
        $proposal = new Proposal(array(
            'uid' => $request->uid,
            'username' => $request->username,
            'paymentid' => $request->paymentid,
            'propose' => $request->propose,
            'current' => $request->current,
            'donate' => $request->donate,
            'note' => $request->note,
            'ipaddress' => $ipAddr,
            // 'txid'=> null,
            // 'txlink'=> null,
            // 'towallet'=> null,
            'completed' => false,
        ));

        $proposal->save();

        //approve payment to Pi server
        //https://api.testnet.minepi.com/
        //$reqURL =  "https://api.minepi.com/v2/payments/{$request->paymentid}/approve";
        //$reqURL = "https://api.minepi.com/v2/payments/" . ($request->paymentid) . "/approve";
        $apikey = "Key " . config('pi.api_key');
        $reqURL = config('pi.payment_api_address') . ($request->paymentid) . "/approve";
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => $apikey]]);
        $res = $client->request('POST', $reqURL);
        // echo $res->getStatusCode();

        //return
        if($res->getStatusCode() == 200){
            $response = response()->json([
                'success' => 'OK',
                'message' => 'The proposal has been accepted, thank you for donation',
                'data' => $proposal,
            ], 200);
            return $response;
        }
        else{
            $response = response()->json([
                'success' => 'NG',
                'message' => 'The proposal approve is not accepted.',
                'data' => $proposal,
            ], 200);
            return $response;
        }
    }

    /*
    //
    onReadyForServerCompletion: (paymentId: string, txid: string) => void,
    //
    */
    public function CompletionPayment(Request $request){
        //validate data
        if ((!$request->paymentid) || (!$request->txid)) {
            if((!$request->paymentid)){
                $message = ['message' => 'CompletionPayment: paymentid is empty !'
                            , "errors" => ["required fields: paymentid, txid"]];
            }
            else{
                $message = ['message' => 'CompletionPayment: txid is empty !'
                            , "errors" => ["required fields: paymentid, txid"]];
            }
            $response = response()->json($message, 402);
            return $response;
        }
        //LOAD proposal base on paymentid and save data
        $proposal = Proposal::where('paymentid', $request->paymentid)->first();
        if($proposal != null){
            $proposal->txid = $request->txid;
            $proposal->status = 1;//1: complete
            $proposal->completed = true;
            $proposal->save();
        }
        else{
            //not found approve -> ?
        }

        //inform complete payment to server
        $apikey = "Key " . config('pi.api_key');
        $reqURL = config('pi.payment_api_address') . ($request->paymentid) . "/complete";
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => $apikey]]);
        $res = $client->request('POST', $reqURL, ['form_params' => ['txid' => $proposal->txid]]);

        // ['body' => ['txid' => $proposal->txid]]
        // ['form_params' => ['txid' => $proposal->txid]]
        // ['headers' => ['Accept' => 'application/json', 'X-Foo' => ['Bar', 'Baz']]
        // $client->post('http://www.example.com/user/create', array('form_params' => array('email' => 'test@gmail.com',)));

        if($res->getStatusCode() == 200){
        //update cache
            if ($proposal && Cache::has('CurrentPiValue')) {
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
            //LastDonateLog
            if ($proposal && Cache::has('LastDonateLog')) {
                $LastDonateLog = Cache::get('LastDonateLog');
                $from_date = $LastDonateLog["from_date"];
                if(($proposal->created_at)->format('Y-m') === $from_date->format('Y-m')) {//check if same month-year
                    $interval = $proposal->created_at->diff($lastlog_time);
                    //update cache
                    $newdata = array(
                        'from_date' => $LastDonateLog["from_date"],
                        'to_date' => $LastDonateLog["to_date"],
                        'id_from' => $LastDonateLog["id_from"],
                        'id_to' => ($proposal->id > $LastDonateLog["id_to"]) ? $proposal->id : $LastDonateLog["id_to"],
                        'all_donate' => $LastDonateLog["all_donate"] + $proposal->donate,
                        'total_donate' => $LastDonateLog["total_donate"] + $proposal->donate,
                        'draw_date' => $LastDonateLog["draw_date"],
                        'drawed_id' => $LastDonateLog["drawed_id"],
                        'drawed_username' => $LastDonateLog["drawed_username"],
                        'paid' => $LastDonateLog["paid"],
                        'txid' => $LastDonateLog["txid"]
                    );
                    Cache::forget('LastDonateLog');
                    Cache::put('LastDonateLog', $newdata);
                }
                else{
                    //clear -> Create LastDonateLog when load homepage
                    Cache::forget('LastDonateLog');
                }
            }
            //return

            $response = response()->json([
                'success' => 'OK',
                'message' => 'The proposal has accepted, thank you for donation',
                'data' => $proposal,
            ], 200);
            return $response;
        }
        else{
            $response = response()->json([
                'success' => 'NG',
                'message' => 'The proposal has not completed.',
                'data' => $proposal,
            ], 200);
            return $response;
        }
    }

    /*
    //
    onCancel: (paymentId: string) => void,
    //
    */
    public function CancelPayment(Request $request){
        //validate data
        if (!$request->paymentid) {
            $message = ['message' => 'CancelPayment: paymentid is empty !'
                        , "errors" => ["required fields: paymentid, txid"]];
            $response = response()->json($message, 402);
            return $response;
        }
        //LOAD proposal base on paymentid and save data
        $proposal = Proposal::where('paymentid', $request->paymentid)->first();
        if($proposal != null){
            $proposal->status = 2;//2: cancel
            $proposal->completed = false;
            $proposal->save();
        }

        //return
        $response = response()->json([
            'success' => 'OK',
            'message' => 'The proposal has canceled.',
            'data' => $proposal,
        ], 200);
        return $response;

    }

    /*
    //
    onError: (error: Error, payment?: PaymentDTO) => void,
    //
    */
    public function ErrorPayment(Request $request){
        //validate data
        if ($request->paymentid) {
            //LOAD proposal base on paymentid and save data
            $proposal = Proposal::where('paymentid', $request->paymentid)->first();
            if($proposal != null){
                $proposal->status = 3;//3: Error
                $proposal->completed = false;
                $proposal->save();
            }
        }
        //return
        $response = response()->json([
            'success' => 'OK',
            'message' => 'The proposal has error and terminate.',
            'data' => $proposal,
        ], 200);
        return $response;

    }

        /*
    //
    onIncompletePaymentFound: Function<PaymentDTO>
    //
    */
    public function InCompletionPayment(Request $request){
        //validate data
        if (!$request->paymentid) {
            $message = ['message' => 'InCompletionPayment: paymentid is empty !'
                        , "errors" => ["required fields: paymentid, txid"]];
            $response = response()->json($message, 402);
            return $response;
        }
        //LOAD proposal base on paymentid and save data
        $proposal = Proposal::where('paymentid', $request->paymentid)->first();
        if($proposal != null){
            if(($request->transaction_verified) && !($request->cancelled)){
                $proposal->txid = $request->txid;
                $proposal->status = 1;//1: complete
                $proposal->completed = true;
            }
            else{
                $proposal->txid = $request->txid;
                $proposal->status = 2;//2: cancel
                $proposal->completed = false;
            }
            $proposal->save();
        }
        else{
            //not found approve -> ?
        }

        //inform complete payment to server
        $apikey = "Key " . config('pi.api_key');
        $reqURL = config('pi.payment_api_address') . ($request->paymentid) . "/complete";
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => $apikey]]);
        $res = $client->request('POST', $reqURL, ['form_params' => ['txid' => $proposal->txid]]);
        // ['body' => ['txid' => $proposal->txid]]
        // ['form_params' => ['txid' => $proposal->txid]]
        // ['headers' => ['Accept' => 'application/json', 'X-Foo' => ['Bar', 'Baz']]
        // $client->post('http://www.example.com/user/create', array('form_params' => array('email' => 'test@gmail.com',)));

        if($res->getStatusCode() == 200){
            //update cache
            if ($proposal && $proposal->completed && Cache::has('CurrentPiValue')) {
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

            //return

            $response = response()->json([
                'success' => 'OK',
                'message' => 'The incompletion proposal has been completed.',
                'data' => $proposal,
            ], 200);
            return $response;
        }
        else{
            $response = response()->json([
                'success' => 'NG',
                'message' => 'The Incompletion proposal has errors',
                'data' => $proposal,
            ], 200);
            return $response;
        }
    }

    public function CheckProposal(Request $request)
    {
        //validate data
        if ((!$request->propose) || (!$request->donate) || (!$request->username)) {
            if((!$request->propose)){
                $message = ['success' => 'NG',
                            'message' => 'Please enter proposal value !',
                            'errors' => ["required fields: propose, donate, username"]];
            }
            else
            if(!$request->donate) {
                $message = ['success' => 'NG',
                            'message' => 'Donate value invalid !',
                            'errors' => ["required fields: propose, donate, username"]];
            }
            else{
                $message = ['success' => 'NG',
                            'message' => 'Unauthorized, open page in Pi browser to enable proposal !',
                            'errors' => ["required fields: propose, donate, username"]];
            }
            $response = response()->json($message, 200);
            return $response;
        }
        $validator = Validator::make($request->all(), [
            'propose' => 'required|numeric|min:0',
            'current' => 'required|numeric|min:0',
            'donate' => 'required|numeric|min:0',
            'username' => 'required|alphaNum',
        ]);
        if ($validator->fails()) {
            $message = ['success' => 'NG',
                        'message' => 'Input data invalid',
                        'errors' => $validator->errors()->all()];
            $response = response()->json($message, 200);
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
        if(abs($realdonate - $request->donate) > 0.01) {
            //fake data;
            $message = ['success' => 'NG',
                        'message' => 'It seems to be your data is out of date',
                        'errors' => ["Please refresh to update current Pi value."]];
            $response = response()->json($message, 200);
            return $response;
        }

        $response = response()->json([
            'success' => 'OK',
            'message' => 'The proposal data is valid',
        ], 200);
        return $response;
    }

///////////////////////////////Reserve
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

    ///
    ///for test purpuse
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

}
