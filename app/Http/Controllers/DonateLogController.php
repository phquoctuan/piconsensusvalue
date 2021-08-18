<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DonateLog;
use App\Proposal;
use \Datetime;

class DonateLogController extends Controller
{
    public function index(Request $request)
    {
        $items = DonateLog::whereNotNull('draw_date')->latest('created_at')->paginate(20);
        if ($request->ajax()) {
            return view('donatelog.donatelog_item', ['items' => $items])->render();
        }
        return view('donatelog.index', compact('items'));
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
            $donatelog['reward'] = number_format(($ThisDonateLog->total_donate)/10, 7);
            $donatelog['remain_donate'] = $ThisDonateLog->remain_donate;
            $donatelog['draw_date'] = $ThisDonateLog->draw_date;
            $donatelog['drawed_id'] = $ThisDonateLog->drawed_id;
            $donatelog['drawed_username'] = $ThisDonateLog->drawed_username;
            $donatelog['paid'] = $ThisDonateLog->paid;
            $donatelog['txid'] = $ThisDonateLog->txid;
        }

        return view('donatelog.luckydraw_result', compact('donatelog'));
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
            $temdate = DateTime::createFromFormat('Y-m-d H:i', $request->draw_date);
            $founditem->draw_date = $temdate;

            $founditem->save();
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
