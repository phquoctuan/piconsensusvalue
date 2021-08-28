<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
Use Alert;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        //
        // $posts = Post::all();
        // return $posts;

        $posts = Post::Latest('created_at')->paginate(10);

        if ($request->ajax()) {
            return $posts;
        }
        return view('blog.index', compact('posts'));

        //dd($posts);
        // $response = Response::json($posts,200);
        // return $response;
        //response()->json(['data' => $posts]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $post = new Post();
        if ($request->method() == "POST") {
            // if($request->has('user_id')) {
            $post->title = $request->title;
            $post->content = $request->content;
            $post->fromdate = $request->fromdate;
            $post->todate = $request->todate;
            $post->status = $request->status;

            $curpass = config('pi.save_password');
            if($curpass != $request->pwd){
                // return redirect('posts/edit/' . $request->id)->with('alert', 'Password not match !');
                return  redirect()->back()->withInput()->with('alert','Password not match !');
            }
            else{
                $post->save();
                return redirect('posts/edit/' . $post->id)->with('success','Saved !');
            }
        }
        else {
            //return empty form;
            return view('blog.create');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if ((!$request->title) || (!$request->content)) {

            $response = Response::json([
                'error' => [
                    'message' => 'Please enter all required fields',
                ],
            ], 422);
            return $response;
        }

        $post = new Post(array(
            'title' => $request->title,
            'content' => $request->content,
            'slug' => Str::slug($request->title, '-'),
        ));

        $post->save();

        $response = Response::json([
            'message' => 'The post has been created succesfully',
            'data' => $post,
        ], 201);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return response()->json([
        //     'name' => 'Pakainfo',
        //     'state' => 'GJ'
        // ]);
        //
        $post = Post::find($id);

        if (!$post) {
            $response = json_encode([
                'error' => [
                    'message' => 'This post cannot be found.',
                ],
            ], 404);
            return $response;
        }

        $response = $post;
        return $response;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // alert('Title','Lorem Lorem Lorem', 'success');
        $post = Post::find($id);
        return view('blog.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $post = Post::find($request->id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->fromdate = $request->fromdate;
        $post->todate = $request->todate;
        $post->status = $request->status;
        $post->slug = Str::slug($request->title, '-');
        $post->save();
        // return $this->edit($request->id);
        return redirect('posts/edit/' . $request->id)->with('success','updated !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);

        if (!$post) {
            $response = Response::json([
                'error' => [
                    'message' => 'The post cannot be found.',
                ],
            ], 404);

            return $response;
        }

        Post::destroy($id);

        $response = Response::json([
            'message' => 'The post has been deleted.',
        ], 200);

        return $response;
    }

    public static function AlertLastActivePost()
    {
        //get last notification
        $post = Post::where('status', 1)
        ->where(function ($query) {
            $query->where('fromdate', '<=', date('Y-m-d H:i:s')) //where('fromdate', '<=', DB::raw('NOW()'))
                ->orWhereNull('fromdate');}
        )
        ->where(function ($query) {
            $query->where('todate', '>=', date('Y-m-d H:i:s'))
                ->orWhereNull('todate');
        })
        ->latest('created_at')->first();

        return view('blog.alert', compact("post"));
    }
}
