<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequet;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\Response;
use Request;
use Response;

//use Validator;

class AuthController extends Controller
{
    //
    public function getRegister()
    {
        return view('auth/ajax_register');
    }

    public function _postRegister()
    {
        ////use Request;
        $a = Request::all();
        $response = Response::json("OK message", 200);
        return $response;
    }
    public function postRegister(HttpRequet $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|min:2',
            'password' => 'required|alphaNum|min:6|same:password_confirmation',
        ]);
        // $validator = $request->validate([
        //     'email' => 'required|email|unique:users,email',
        //     'name' => 'required|min:2',
        //     'password' => 'required|alphaNum|min:6|same:password_confirmation',
        // ]);
        if ($validator->fails()) {
            $message = ['errors' => $validator->errors()->all()];
            $response = Response::json($message, 202);
        } else {
            // Create a new user
            // $user = new User([
            //     'name' => $request->name,
            //     'email' => $request->email,
            //     'facebook_id' => $request->email,
            // ]);
            // $user->save();
            // Auth::login($user);
            $message = ['success' => 'Thank you for joining us!', 'url' => '/', 'name' => $request->name];
            $response = Response::json($message, 200);
        }
        return $response;

    }
    public function getLogin()
    {
        return view('auth/ajax_login');
    }

    public function postLogin(HttpRequet $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $message = ['errors' => $validator->messages()->all()];
            $response = Response::json($message, 202);
        } else {
            $remember = $request->remember ? true : false;
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
                $message = ['success' => 'Love to see you here!', 'url' => '/'];
                $response = Response::json($message, 200);
            } else {
                $message = ['errors' => 'Please check your email or password again.'];
                $response = Response::json($message, 202);
            }
        }
        return $response;
    }

}
