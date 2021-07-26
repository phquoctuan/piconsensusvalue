<?php

namespace App\Http\Controllers;

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

class ProposalController extends Controller
{
    public function currentValue(CurrentValueInterface $currentvalue)
    {
        if (Cache::has('CurrentPiValue')) {

        }
        else{
            Cache::put('CurrentPiValue', '1', 60);
        }
        $randomId = Cache::get('CurrentPiValue');
        Cache::increment('CurrentPiValue');
        $response = response()->json(['current_value' => $randomId], 200);
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
        //
        //dd($request->propose);
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

        try
        {
            $proposal = new Proposal(array(
                'propose' => $request->propose,
                'current' => $request->current,
                'donate' => $request->donate,
                'public_key' => $request->publickey,
                'note' => $request->note,
            ));
            // $proposal->save();

            //Lanin debugger as a helper
            //lad($proposal);
            //Lanin debugger as a facade
            //Debugger::dump($proposal);

            $response = response()->json([
                'success' => 'OK',
                'message' => 'The proposal has been accepted, thank you for donation',
                'data' => $proposal,
            ], 201);
        }
        catch(Exception $e)
        {
            $message = ['message' => $e->getMessage()];
            $response = response()->json($message, 500);
            return $response;
        }
        return $response;
    }
}
