<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DonateLog;
use App\Proposal;
use \Datetime;
use App\Settings;
use Illuminate\Support\Facades\Cache;

class DonateLogController extends Controller
{
    public function index(Request $request)
    {
        $items = DonateLog::whereNotNull('draw_date')->latest('created_at')->paginate(20);
        if ($request->ajax()) {
            return view('donatelog.donatelog_item', ['items' => $items])->render();
        }
        $lucky2_enable = 0;
        $setting = Settings::where("attribute","lucky2_enable")->first();
        if($setting != null){
            if($setting->value == "1")
                $lucky2_enable = 1;
        }
        $lucky3_enable = 0;
        $setting1 = Settings::where("attribute","lucky3_enable")->first();
        if($setting1 != null){
            if($setting1->value == "1")
                $lucky3_enable = 1;
        }
        // dd($lucky2_enable);
        return view('donatelog.index', compact('items', 'lucky2_enable', 'lucky3_enable'));
    }

    public function LuckyDrawSelect()
    {
        return view('donatelog.luckydraw_select');
    }

    public function LuckyDrawResult(Request $request)
    {
        $nummonth = $request->select_month;
        $numyear = $request->select_year;
        $num_padded = sprintf("%02d", $nummonth);
        $donatelog = array(
            'select_month' => $num_padded,
            'select_year' => $request->select_year
        );
        //query donate log
        $firstdaystr = date("Y-m-01");
        $fromdate = new DateTime($firstdaystr);
        $fromdate->setDate($numyear, $nummonth, 1);
        // $fromdate = mktime(0, 0, 0, $nummonth, 1, $numyear);
        // echo $fromdate->format('Y-m-d');
        $ThisDonateLog = DonateLog::where('from_date', $fromdate)->first();
        if($ThisDonateLog == null){
            $donatelog["has_donatelog"] = 0;
        }
        else
        {
            $donatelog["has_donatelog"] = 1;
            //donatelog data
            $donatelog["id"] = $ThisDonateLog->id;
            $donatelog["from_date"] = $ThisDonateLog->from_date;
            $donatelog['to_date'] = $ThisDonateLog->to_date;
            $donatelog['id_from'] = $ThisDonateLog->id_from;
            $donatelog['id_to'] = $ThisDonateLog->id_to;
            $donatelog['total_propose'] = $ThisDonateLog->total_propose;
            $donatelog['count_donate'] = $ThisDonateLog->count_donate;
            $donatelog['total_donate'] = $ThisDonateLog->total_donate;
            $donatelog['remain_donate'] = $ThisDonateLog->remain_donate;
            $donatelog['draw_date'] = $ThisDonateLog->draw_date;
            $donatelog['reward'] = number_format(($ThisDonateLog->reward), 7);
            $donatelog['drawed_id'] = $ThisDonateLog->drawed_id;
            $donatelog['drawed_username'] = $ThisDonateLog->drawed_username;
            $donatelog['paid'] = $ThisDonateLog->paid;
            $donatelog['txid'] = $ThisDonateLog->txid;
            $donatelog['fee'] = $ThisDonateLog->fee;
            $donatelog['fixed_drawdate'] = $ThisDonateLog->fixed_drawdate;
            $donatelog['live_drawlink'] = $ThisDonateLog->live_drawlink;
            $donatelog['reward2'] = number_format(($ThisDonateLog->reward2), 7);
            $donatelog['drawed_id2'] = $ThisDonateLog->drawed_id2;
            $donatelog['drawed_username2'] = $ThisDonateLog->drawed_username2;
            $donatelog['paid2'] = $ThisDonateLog->paid2;
            $donatelog['txid2'] = $ThisDonateLog->txid2;
            $donatelog['fee2'] = $ThisDonateLog->fee2;
            $donatelog['reward3'] = number_format(($ThisDonateLog->reward3), 7);
            $donatelog['drawed_id3'] = $ThisDonateLog->drawed_id3;
            $donatelog['drawed_username3'] = $ThisDonateLog->drawed_username3;
            $donatelog['paid3'] = $ThisDonateLog->paid3;
            $donatelog['txid3'] = $ThisDonateLog->txid3;
            $donatelog['fee3'] = $ThisDonateLog->fee3;
        }

        $lucky2_enable = 0;
        $setting = Settings::where("attribute","lucky2_enable")->first();
        if($setting != null){
            if($setting->value == "1")
                $lucky2_enable = 1;
        }
        $lucky3_enable = 0;
        $setting1 = Settings::where("attribute","lucky3_enable")->first();
        if($setting1 != null){
            if($setting1->value == "1")
                $lucky3_enable = 1;
        }

        return view('donatelog.luckydraw_result', compact('donatelog', 'lucky2_enable', 'lucky3_enable'));
    }

    public function GetUserByProposalId(Request $request)
    {
        if (!$request->proposal_id) {
            $message = [
                        "success" => 'NG',
                        "message" => 'Please enter proposal Id.',
                        "errors" => ["required fields: proposal_id"]];
            $response = response()->json($message, 200);
            return $response;
        }
        $founditem = Proposal::find($request->proposal_id);
        if($founditem != NULL){
            $message = [
                "success" => 'OK',
                "message" => 'found.',
                "data" =>  ["username" => $founditem->username]
            ];
            $response = response()->json($message, 200);
            return $response;
        }
        else{
            $message = [
                "success" => 'NG',
                'message' => 'Username with this proposal id not found.'
                , "errors" => ["not found"]];
            $response = response()->json($message, 200);
            return $response;
        }

    }

    public function SaveLuckyDraw(Request $request)
    {
        //check password
        if (!$request->pwd) {
            $message = [
                        "success" => 'NG',
                        "message" => 'Please enter password to save data.',
                        "errors" => ["required fields: password"]];
            $response = response()->json($message, 200);
            return $response;
        }
        else{
            $curpass = config('pi.save_password');
            if($curpass != $request->pwd){
                $message = [
                    "success" => 'NG',
                    "message" => 'Password not match.',
                    "errors" => ["required fields: password"]];
                $response = response()->json($message, 200);
                return $response;
            }
        }

        if (!$request->donatelog_id) {
            $message = [
                        "success" => 'NG',
                        "message" => 'Invalid donate log id.',
                        "errors" => ["required fields: donatelog_id"]];
            $response = response()->json($message, 200);
            return $response;
        }
        //find data
        $founditem = DonateLog::find($request->donatelog_id);
        if($founditem != NULL){
            $founditem->drawed_id = $request->drawed_id;
            $founditem->drawed_username = $request->drawed_username;
            if($request->paid =="true"){
                $founditem->paid = 1;
            }
            else{
                $founditem->paid = 0;
            }
            $founditem->txid = $request->txid;
            //draw_date
            if($request->draw_date == ""){
                $founditem->draw_date = null;
            }
            else{
            $temdate = DateTime::createFromFormat('Y-m-d H:i', $request->draw_date);
            $founditem->draw_date = $temdate;
            }
            $founditem->reward = $request->reward;
            $founditem->fee = $request->fee;
            if($request->fixed_drawdate =="true"){
                $founditem->fixed_drawdate = 1;
            }
            else{
                $founditem->fixed_drawdate = 0;
            }
            $founditem->live_drawlink = $request->live_drawlink;
            //lucky 2
            if ($request->has('lucky2_enable') && $request->lucky2_enable == 1) {
                $founditem->reward2 = $request->reward2;
                $founditem->drawed_id2 = $request->drawed_id2;
                $founditem->drawed_username2 = $request->drawed_username2;
                if($request->paid2 =="true"){
                    $founditem->paid2 = 1;
                }
                else{
                    $founditem->paid2 = 0;
                }
                $founditem->txid2 = $request->txid2;
                $founditem->fee2 = $request->fee2;
            }
            //lucky 3
            if ($request->has('lucky3_enable') && $request->lucky3_enable == 1) {
                $founditem->reward3 = $request->reward3;
                $founditem->drawed_id3 = $request->drawed_id3;
                $founditem->drawed_username3 = $request->drawed_username3;
                if($request->paid3 =="true"){
                    $founditem->paid3 = 1;
                }
                else{
                    $founditem->paid3 = 0;
                }
                $founditem->txid3 = $request->txid3;
                $founditem->fee3 = $request->fee3;
            }
            $founditem->save();

            Cache::forget('LastDonateLog');
            Cache::forget('LastMonthDonateLog');

            $message = [
                "success" => 'OK',
                "message" => 'Data has been saved successfully.',
                "data" =>  ["username" => $founditem->username]
            ];
            $response = response()->json($message, 200);
            return $response;
        }
        else{
            $message = [
                "success" => 'NG',
                'message' => 'Data not found by this Id.'
                , "errors" => ["not found"]];
            $response = response()->json($message, 200);
            return $response;
        }

    }
}
