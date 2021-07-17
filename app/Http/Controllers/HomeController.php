<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SweetAlert;


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
        return view('home');
        //return Redirect::();
    }
}
