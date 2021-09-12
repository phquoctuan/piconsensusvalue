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
use App\DonateLog;
use App\Settings;
use Illuminate\Support\Facades\DB;
use GuzzleHttp;
use \Datetime;
use phpDocumentor\Reflection\Types\Null_;
use PhpParser\Node\Expr\Cast\Double;

class ProposalController extends Controller
{

    public function index(Request $request)
    {
        $items = Proposal::where('completed','1')->latest('created_at')->paginate(20);
        if ($request->ajax()) {
            return view('proposal.proposal_item', ['items' => $items])->render();
        }
        return view('proposal.index', compact('items'));
    }

    public function currentValue()
    {
        if (!Cache::has('CurrentPiValue')) {
            //calculate current value
            //1: get lasttest PiValueLog
            // $lastlog = PiValueLog::orderBy('propose_time', 'desc')->first();//PiValueLog::latest();
            $lastlog = PiValueLog::orderBy('propose_id', 'desc')->first();//PiValueLog::latest();
            if ($lastlog){
                $new_last_log = false;
                $new_proposals = Proposal::select(DB::raw("COUNT(*) AS total_propose, SUM(propose) AS sum_propose, SUM(donate) AS sum_donate, MAX(id) AS max_id, MAX(created_at) AS propose_time"))
                ->where('completed','1')
                ->where('id', '>',  $lastlog->propose_id)
                ->first();
                if($new_proposals->total_propose > 0){
                    $new_last_log = true;
                    $new_total_propose = $lastlog->total_propose + $new_proposals->total_propose;
                    $new_sum_donate = $lastlog->sum_donate + $new_proposals->sum_donate;
                    $new_pivalue = (($lastlog->current_value * $lastlog->total_propose) + $new_proposals->sum_propose)/$new_total_propose;
                    $new_propose_time = $new_proposals->propose_time;
                    $new_propose_id = $new_proposals->max_id;
                }
                else{ //no new proposal data
                    $new_total_propose = $lastlog->total_propose;
                    $new_sum_donate = $lastlog->sum_donate;
                    $new_pivalue = $lastlog->current_value;
                    $new_propose_time = $lastlog-> propose_time;
                    $new_propose_id = $lastlog->propose_id;
                }
                //save new PiValueLog
                $newdata = array(
                    'current_value' => $new_pivalue,
                    'total_propose' => $new_total_propose,
                    'sum_donate' => $new_sum_donate,
                    'propose_time' => $new_propose_time,
                    'propose_id' => $new_propose_id
                );

                if($new_last_log){
                    $CurrentPiValue = new PiValueLog($newdata);
                    $CurrentPiValue->save();
                }
                //save cache
                $newdata["lastlog_time"] = $new_propose_time;
                // $newdata["id_to"] = $new_proposals->max_id;

                Cache::put('CurrentPiValue', $newdata);

            }
            else{//no last log in database
                //calculate everage value base all proposal
                $new_proposals = Proposal::select(DB::raw("COUNT(*) AS total_propose, SUM(propose) AS sum_propose, SUM(donate) AS sum_donate, MAX(id) AS max_id , MAX(created_at) AS propose_time"))
                ->where('completed','1')
                ->first();

                if($new_proposals->total_propose > 0){
                    //dd($new_proposals->total_propose);
                    $new_total_propose = $new_proposals->total_propose;
                    $new_sum_donate = $new_proposals->sum_donate;
                    $new_pivalue = $new_proposals->sum_propose/$new_proposals->total_propose;
                    $new_propose_time = $new_proposals->propose_time;
                    $new_propose_id = $new_proposals->max_id;
                }
                else{// no data at all
                    $new_total_propose = 0;
                    $new_sum_donate = 0;
                    $new_pivalue = 0;
                    $new_propose_time = null;
                    $new_propose_id = 0;
                }
                //save new PiValueLog
                $newdata = array(
                    'current_value' => $new_pivalue,
                    'total_propose' => $new_total_propose,
                    'sum_donate' => $new_sum_donate,
                    'propose_time' => $new_propose_time,
                    'propose_id' => $new_propose_id
                );
                $CurrentPiValue = new PiValueLog($newdata);
                if($CurrentPiValue->propose_time != null)
                {
                    $CurrentPiValue->save();
                }
                $newdata["lastlog_time"] = $new_propose_time;
                // $newdata["id_to"] = $new_proposals->max_id;
                Cache::put('CurrentPiValue', $newdata);
            }
        }

        $cacheValue = Cache::get('CurrentPiValue');

        //caculate this month;
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
        $pival = $cacheValue["current_value"];
        if($pival > 999999){
            $strcurpival = number_format($pival,2);
        }
        else if ($pival > 99999){
            $strcurpival = number_format($pival,3);
        }
        else if ($pival > 9999){
            $strcurpival = number_format($pival,4);
        }
        else{
            $strcurpival = number_format($pival,5);
        }
        $response = response()->json(['current_value' => $cacheValue["current_value"],
                                        'current_value_str' => $strcurpival,
                                        'sum_donate' => $cacheValue["sum_donate"],
                                        'total_propose' => $cacheValue["total_propose"],
                                        'thismonth_id_from' => $ThisMonthDonate["id_from"],
                                        'thismonth_id_to' => $ThisMonthDonate["id_to"],
                                        'thismonth_count_donate' => $ThisMonthDonate["count_donate"],
                                        'thismonth_total_donate' => $ThisMonthDonate["total_donate"],
                                        'thismonth_reward' => $ThisMonthDonate["reward"]
                                        ], 200);
        return $response;
    }

    /*
    //
    */
    public function LastMonthDonate()
    {
        $refeshcache = false;
        $firstdaystr = date("Y-m-01");//first day of month
        $firstday = new DateTime($firstdaystr);
        // return response()->json($firstday, 200);
        $firstdaylastmonth = $firstday;
        $firstdaylastmonth->modify('-1 months');
        $lastdaylastmonth = new DateTime($firstdaylastmonth->format('Y-m-t'));

        if(!Cache::has('LastMonthDonateLog')){
            $refeshcache = true;
        }
        else{
            $cacheValue = Cache::get('LastMonthDonateLog');
            $d1 = new DateTime($cacheValue['from_date']);
            if($d1 != $firstdaylastmonth)
            {
                $refeshcache = true;
            }
        }

        if ($refeshcache) {
            //1: get LastDonateLog
            // $timezone = date_default_timezone_get();
            $LastMonthDonateLog = DonateLog::where('from_date', $firstdaylastmonth)->first();
            if($LastMonthDonateLog == null){
                lad("no log");
                $newdata = array(
                    'from_date' => $firstdaylastmonth,//->format("Y-m-d")
                    'to_date' => $lastdaylastmonth,
                    'id_from' => 0,
                    'id_to' => 0,
                    'total_propose' => 0,
                    'count_donate' => 0,
                    'total_donate' => 0,
                    'reward' => null,
                    'remain_donate' => null,
                    'draw_date' => $firstday,
                    'drawed_id' => null,
                    'drawed_username' => null,
                    'paid' => 0,
                    'txid' => null,
                    'fixed_drawdate' => 0,
                    'live_drawlink' => "",
                    'reward2' => null,
                    'drawed_id2' => null,
                    'drawed_username2' => null,
                    'paid2' => 0,
                    'txid2' => null,
                    'reward3' => null,
                    'drawed_id3' => null,
                    'drawed_username3' => null,
                    'paid3' => 0,
                    'txid3' => null,
                );
                $LastMonthDonateLog = new DonateLog($newdata);
            }
            //sum donation for last month
            if($LastMonthDonateLog->drawed_id == null){ //not drawed yet

                $lastmonth_proposals = Proposal::select(DB::raw("COUNT(*) AS count_propose, SUM(propose) AS sum_propose , SUM(donate) AS sum_donate, MIN(id) AS min_id ,MAX(id) AS max_id"))
                ->where('created_at', '>=', $firstdaylastmonth)
                ->where('created_at', '<=',  $lastdaylastmonth)
                ->where('completed','1')
                ->first();

                if($lastmonth_proposals != null && $lastmonth_proposals->count_propose > 0){
                    $LastMonthDonateLog->count_donate = $lastmonth_proposals->count_propose;
                    $LastMonthDonateLog->id_from = $lastmonth_proposals->min_id;
                    $LastMonthDonateLog->id_to = $lastmonth_proposals->max_id;
                    $LastMonthDonateLog->total_propose = $lastmonth_proposals->sum_propose;
                    $LastMonthDonateLog->total_donate = $lastmonth_proposals->sum_donate;
                    $LastMonthDonateLog->save();
                }
            }
            // echo(gettype($LastDonateLog->from_date));
            // echo ($LastDonateLog->from_date);
            //Cache data

            $cachedata = array(
                'from_date' => $LastMonthDonateLog->from_date,//->format('d/m/Y')
                'to_date' => $LastMonthDonateLog->to_date,
                'id_from' => $LastMonthDonateLog->id_from,
                'id_to' => $LastMonthDonateLog->id_to,
                'total_propose' => $LastMonthDonateLog->total_propose,
                'count_donate' => $LastMonthDonateLog->count_donate,
                'total_donate' => $LastMonthDonateLog->total_donate,
                'reward' => number_format(($LastMonthDonateLog->total_donate)/10, 7),
                'remain_donate' => $LastMonthDonateLog->remain_donate,
                'draw_date' => $LastMonthDonateLog->draw_date,
                'drawed_id' => $LastMonthDonateLog->drawed_id,
                'drawed_username' => $LastMonthDonateLog->drawed_username,
                'paid' => $LastMonthDonateLog->paid,
                'txid' => $LastMonthDonateLog->txid,
                'fixed_drawdate' => $LastMonthDonateLog->fixed_drawdate,
                'live_drawlink' => $LastMonthDonateLog->live_drawlink,
                'reward2' => $LastMonthDonateLog->reward2,
                'drawed_id2' => $LastMonthDonateLog->drawed_id2,
                'drawed_username2' => $LastMonthDonateLog->drawed_username2,
                'paid2' => $LastMonthDonateLog->paid2,
                'txid2' => $LastMonthDonateLog->txid2,
                'reward3' => $LastMonthDonateLog->reward3,
                'drawed_id3' => $LastMonthDonateLog->drawed_id3,
                'drawed_username3' => $LastMonthDonateLog->drawed_username3,
                'paid3' => $LastMonthDonateLog->paid3,
                'txid3' => $LastMonthDonateLog->txid3,
            );
            Cache::forget('LastMonthDonateLog');
            Cache::put('LastMonthDonateLog', $cachedata);
        }

        $cacheValue = Cache::get('LastMonthDonateLog');
        // lad($cacheValue);
        $response = response()->json($cacheValue, 200);
        return $response;
    }
    /*
    //
    */
    public function ThisMonthDonate()
    {
        if (!Cache::has('LastDonateLog')) {
            //1: get LastDonateLog
            // $timezone = date_default_timezone_get();
            $firstdaystr = date("Y-m-01");//first day of month
            $firstday = new DateTime($firstdaystr);
            // return response()->json($firstday, 200);
            $lastdaystr = date("Y-m-t");//last day of month
            $lastday = new DateTime($lastdaystr);
            $draw_date = clone $lastday;
            $draw_date->modify('+1 day');

            $LastDonateLog = DonateLog::where('from_date', $firstday)->first();
            if($LastDonateLog == null){
                //lad("no log");
                $newdata = array(
                    'from_date' => $firstday,//->format("Y-m-d")
                    'to_date' => $lastday,
                    'id_from' => 0,
                    'id_to' => 0,
                    'total_propose' => 0,
                    'count_donate' => 0,
                    'total_donate' => 0,
                    'reward' => null,
                    'remain_donate' => null,
                    'draw_date' => $draw_date,
                    'drawed_id' => null,
                    'drawed_username' => null,
                    'paid' => 0,
                    'txid' => null,
                    'fixed_drawdate' => 0,
                    'live_drawlink' => ""
                );
                $LastDonateLog = new DonateLog($newdata);
                $LastDonateLog->save();
            }
            //sum donation for this month
            $thismonth_proposals = Proposal::select(DB::raw("COUNT(*) AS count_propose, SUM(propose) AS sum_propose , SUM(donate) AS sum_donate, MIN(id) AS min_id ,MAX(id) AS max_id"))
            ->where('created_at', '>=', $firstday)
            ->where('created_at', '<=',  $lastday)
            ->where('completed','1')
            ->first();
        // dd($thismonth_proposals->sum_donate);
            $LastDonateLog->count_donate = $thismonth_proposals->count_propose;
            $LastDonateLog->id_from = $thismonth_proposals->min_id;
            $LastDonateLog->id_to = $thismonth_proposals->max_id;
            $LastDonateLog->total_propose = $thismonth_proposals->sum_propose ?? 0;
            $LastDonateLog->total_donate = $thismonth_proposals->sum_donate ?? 0;
            $LastDonateLog->save();

            //Cache data
            $cachedata = array(
                'from_date' => $LastDonateLog->from_date,//->format('d/m/Y')
                'to_date' => $LastDonateLog->to_date,
                'id_from' => $LastDonateLog->id_from,
                'id_to' => $LastDonateLog->id_to,
                'total_propose' => $LastDonateLog->total_propose,
                'count_donate' => $LastDonateLog->count_donate,
                'total_donate' => $LastDonateLog->total_donate,
                'reward' => $LastDonateLog->total_donate/10,
                'remain_donate' => $LastDonateLog->remain_donate,
                'draw_date' => $LastDonateLog->draw_date,
                'drawed_id' => $LastDonateLog->drawed_id,
                'drawed_username' => $LastDonateLog->drawed_username,
                'paid' => $LastDonateLog->paid,
                'txid' => $LastDonateLog->txid,
                'fixed_drawdate' => $LastDonateLog->fixed_drawdate,
                'live_drawlink' => $LastDonateLog->live_drawlink,
            );
            Cache::forget('LastDonateLog');
            Cache::put('LastDonateLog', $cachedata);
        }

        $cacheValue = Cache::get('LastDonateLog');
        // lad($cacheValue);
        $response = response()->json($cacheValue, 200);
        return $response;
    }

    /*
    //
    onReadyForServerApproval: (paymentId: string) => void,
    //
    */
    public function ApprovalPayment(Request $request){
        //validate data
        if (($request->propose == null) || (!$request->donate) || (!$request->paymentid)) {
            if(($request->propose == null)){
                $message = ['message' => __('Please enter proposal value !')
                            , "errors" => ["required fields: propose, donate"]];
            }
            else
            if(!$request->donate) {
                $message = ['message' => __('Donate value invalid !')
                            , "errors" => ["required fields: propose, donate"]];
            }
            else{
                $message = ['message' => __('PaymentId is empty !')
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
            $message = ['message' => __('Input data invalid')
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

        $realdonate = $this->CalculateDonateAmount($request->propose, $curpival);

        if(abs($realdonate - $request->donate) > 0.01) {
            //fake data;
            $message = ['message' => __('It seems to be your data is out of date.')
            , "errors" => [__('Please refresh to update current Pi value.')]];
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
                'message' => __('The proposal has been accepted, thank you for donation.'),
                'data' => $proposal,
            ], 200);
            return $response;
        }
        else{
            $response = response()->json([
                'success' => 'NG',
                'message' => __('The proposal approve is not accepted.'),
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
                $message = ['message' => __('CompletionPayment: paymentid is empty !')
                            , "errors" => ["required fields: paymentid, txid"]];
            }
            else{
                $message = ['message' => __('CompletionPayment: txid is empty !')
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
            //update cache CurrentPiValue
            if ($proposal && Cache::has('CurrentPiValue')) {
                $cacheValue = Cache::get('CurrentPiValue');
                //calculate new pi value
                $total_propose = $cacheValue["total_propose"];
                $new_total_propose = $total_propose + 1;
                $new_sum_donate = $cacheValue["sum_donate"] + $proposal->donate;
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
                            'sum_donate' => $new_sum_donate,
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
                CacheLastDonateLogSameMonth($proposal);
            }
            //return

            $response = response()->json([
                'success' => 'OK',
                'message' => __('The proposal has been accepted, thank you for donation.'),
                'data' => $proposal,
            ], 200);
            return $response;
        }
        else{
            $response = response()->json([
                'success' => 'NG',
                'message' => __('The proposal has not completed.'),
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
            $message = ['message' => __('CancelPayment: paymentid is empty !')
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
            'message' => __('The proposal has canceled.'),
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
            'message' => __('The proposal has error and terminate.'),
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
            $message = ['message' => __('InCompletionPayment: paymentid is empty !')
                        , "errors" => ["required fields: paymentid, txid"]];
            $response = response()->json($message, 402);
            return $response;
        }
        //LOAD proposal base on paymentid and save data
        $proposal = Proposal::where('paymentid', $request->paymentid)->first();
        if($proposal != null){
            if(($request->transaction_verified == "true") && ($request->cancelled == "false")){
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
                $new_sum_donate = $cacheValue["sum_donate"] + $proposal->donate;
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
                            'sum_donate' => $new_sum_donate,
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
            if ($proposal && $proposal->completed && Cache::has('LastDonateLog')) {
                CacheLastDonateLogSameMonth($proposal);
            }
            //return

            $response = response()->json([
                'success' => 'OK',
                'message' => __('The incompletion ... Your proposal Id is:') . $proposal->id,
                'data' => $proposal,
            ], 200);
            return $response;
        }
        else{
            $response = response()->json([
                'success' => 'NG',
                'message' => __('The Incompletion proposal has errors'),
                'data' => $proposal,
            ], 200);
            return $response;
        }
    }

    //
    private function CalculateDonateAmount(float $proposalvalue,float $currentvalue) {
        $donatePi = 0.00001;
        $numDecimal = 5;
        if($proposalvalue === null || $currentvalue === null)
        {
            return number_format($donatePi,$numDecimal);
        }
        $diff = abs($proposalvalue - $currentvalue);
        if($diff != 0) {
            if ($currentvalue == 0) {
                $donatePi = $diff/(10 * $proposalvalue);
            }
            else{
                $donatePi = $diff/(10 * $currentvalue);
            }
        }
        else{
            if ($currentvalue == 0){
                $donatePi = 0.1;
            }
            else{
                if($currentvalue < 10){
                    $donatePi = 0.1;
                }
                else{
                    $donatePi = 1/$currentvalue;
                }
            }
        }
        if($donatePi < 0.00001)
        {
            $donatePi = 0.00001;
        }
        // dd($donatePi);
        return number_format($donatePi, $numDecimal);
    }

    public function CheckProposal(Request $request)
    {
        //check enable propose
        $enable_proposal = Cache::rememberForever('enable_proposal', function() {
            // Laravel >= 5.2, use 'lists' instead of 'pluck' for Laravel <= 5.1
            $enable_setting = Settings::where("attribute","app_enable")->first();
            if($enable_setting != null){
                if($enable_setting->value == "1")
                    return true;
                else
                    return false;
            }
            else{
                return true;
            }
        });
        if(!$enable_proposal){
            $message = ['success' => 'NG',
                        'message' => __('Proposal is temporarily disabled'),
                    ];
            $response = response()->json($message, 200);
            return $response;
        }

        //validate data
        if (($request->propose == null) || (!$request->donate) || (!$request->username)) {
            if(($request->propose == null)){
                $message = ['success' => 'NG',
                            'message' => __('Please enter proposal value !'),
                            'errors' => ["required fields: propose, donate, username"]];
            }
            else
            if(!$request->donate) {
                $message = ['success' => 'NG',
                            'message' => __('Donate value invalid !'),
                            'errors' => ["required fields: propose, donate, username"]];
            }
            else{
                $message = ['success' => 'NG',
                            'message' => __('Unauthorized, open page in Pi browser to enable proposal !'),
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
                        'message' => __('Input data invalid'),
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

        $realdonate = $this->CalculateDonateAmount($request->propose, $curpival);

        if(abs($realdonate - $request->donate) > 0.01) {
            //fake data;
            $message = ['success' => 'NG',
                        'message' => __('It seems to be your data is out of date.'),
                        'errors' => ["Please refresh to update current Pi value."]];
            $response = response()->json($message, 200);
            return $response;
        }

        $response = response()->json([
            'success' => 'OK',
            'message' => __('The proposal data is valid'),
        ], 200);
        return $response;
    }


///////////////////////////////Reserve
    public function create(Request $request)
    {
        //validate data
        if ((!$request->propose) || (!$request->publickey)) {
            if((!$request->propose)){
                $message = ['message' => __('Please enter proposal value !')
                            , "errors" => ["required fields: propose, publickey"]];
            }
            else {
                $message = ['message' => __('Please enter all required fields')
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
            $message = ['message' => __('Input data invalid')
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
            $message = ['message' => __('It seems to be your data is out of date.')
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
            $new_sum_donate = $cacheValue["sum_donate"] + $proposal->donate;
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
                        'sum_donate' => $new_sum_donate,
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
            'message' => __('The proposal has been accepted, thank you for donation.'),
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

function CacheLastDonateLogSameMonth(Proposal  $proposal) {
    $LastDonateLog = Cache::get('LastDonateLog');
    $from_date = $LastDonateLog["from_date"];
    if(($proposal->created_at)->format('Y-m') === $from_date->format('Y-m')) {//check if same month-year
        //update cache
        $newdata = array(
            'from_date' => $LastDonateLog["from_date"],
            'to_date' => $LastDonateLog["to_date"],
            'id_from' => ($LastDonateLog["id_from"] != null) ? $LastDonateLog["id_from"] : $proposal->id,
            'id_to' => ($proposal->id > $LastDonateLog["id_to"]) ? $proposal->id : $LastDonateLog["id_to"],
            'total_propose' => $LastDonateLog["total_propose"] + $proposal->propose,
            'count_donate' => $LastDonateLog["count_donate"] + 1,
            'total_donate' => $LastDonateLog["total_donate"] + $proposal->donate,
            'reward' => ($LastDonateLog["total_donate"] + $proposal->donate)/10, //$LastDonateLog["reward"],
            'remain_donate' => $LastDonateLog["remain_donate"] + $proposal->donate,
            'draw_date' => $LastDonateLog["draw_date"],
            'drawed_id' => $LastDonateLog["drawed_id"],
            'drawed_username' => $LastDonateLog["drawed_username"],
            'paid' => $LastDonateLog["paid"],
            'txid' => $LastDonateLog["txid"],
            'fixed_drawdate' => $LastDonateLog["fixed_drawdate"],
            'live_drawlink' => $LastDonateLog["live_drawlink"]
        );
        Cache::forget('LastDonateLog');
        Cache::put('LastDonateLog', $newdata);
    }
    else{
        //clear -> Create LastDonateLog when load homepage
        Cache::forget('LastDonateLog');
    }
}
