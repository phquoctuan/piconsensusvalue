<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        //
        // $posts = Post::all();
        // return $posts;

        $settings = Settings::Latest('id')->paginate(10);

        if ($request->ajax()) {
            return $settings;
        }
        return view('settings.index', compact('settings'));

    }

    public function edit($id)
    {
        // alert('Title','Lorem Lorem Lorem', 'success');
        $setting = Settings::find($id);
        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
            $setting = Settings::find($request->id);
            if($setting != null){
                $setting->attribute = $request->attribute;
                $setting->value = $request->value;
                $setting->save();
                Cache::forget('enable_proposal');
            }
            // return $this->edit($request->id);
            return redirect('settings/edit/' . $request->id)->with('success','Saved !');;
    }
}
