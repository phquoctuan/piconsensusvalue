<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class LangController extends Controller
{
    private $langActive = [
        'vi',
        'en',
        'jp',
        'cn'
    ];
    public function changeLang(Request $request, $lang)
    {
        if (in_array($lang, $this->langActive)) {
            $request->session()->put(['lang' => $lang]);
            return redirect()->back();
            // return url()->previous();
            //return back()->withInput();
            //return redirect()->getUrlGenerator()->previous()
            //redirect()->back()->getTargetUrl()
            //return redirect()->route('profile', [$user]);
        }
    }

}
