<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Statictis;
use App\Proposal;

class StatictisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $projects = statictis::paginate(20);
        return view('statictis.index', compact('projects'))
            ->with('i', (request()->input('page', 1) - 1) * 20);

                // load the view and pass the sharks
                // return view::make('statictis.index')
                // return view('statictis.index')
                //     ->with('sharks', $sharks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // load the create form (app/views/statictis/create.blade.php)
        return view('statictis.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required',
            'from' => 'required'
        ]);

        statictis::create($request->all());

        return redirect()->route('statictis.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $curstatictis = statictis::find($id);
        return view('statictis.show', compact('curstatictis'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $curstatictis = statictis::find($id);
        return view('statictis.edit', compact('curstatictis'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'label' => 'required',
            'from' => 'required'
        ]);
        $curstatictis = statictis::find($id);
        $curstatictis->label = $request->label;
        $curstatictis->from = $request->from;
        $curstatictis->to = $request->to;
        $curstatictis->total = $request->total;
        $curstatictis->save();

        // echo "ssss";
        // die();
        // redirect
        // Session::flash('message', 'Successfully updated shark!');
        // return redirect::to('sharks');
        return redirect()->route('statictis.index')
            ->with('success', 'Statictis updated successfully');

        // $curstatictis->update($request->all());
        // return redirect()->route('statictis.index')
        //     ->with('success', 'Statictis updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $curstatictis = statictis::find($id);
        $curstatictis->delete();

        return redirect()->route('statictis.index')
            ->with('success', 'statictis deleted successfully');
    }

    public function ComputeStatictis()
    {

        $statictises = statictis::get();
        foreach ($statictises as $curstatictis) {
            if($curstatictis->to != null){
                $proposals = Proposal::select(DB::raw("COUNT(*) AS total_propose"))
                ->where('completed','1')
                ->where('propose', '>=',  $curstatictis->from)
                ->where('propose', '<',  $curstatictis->to)
                ->first();
                $curstatictis->total = $proposals->total_propose;
            }
            else{
                $proposals = Proposal::select(DB::raw("COUNT(*) AS total_propose"))
                ->where('completed','1')
                ->where('propose', '>=',  $curstatictis->from)
                ->first();
                $curstatictis->total = $proposals->total_propose;
            }
            $curstatictis->save();
        }

        return redirect()->route('statictis.index')
            ->with('success', 'Statictis updated successfully');

    }

}
