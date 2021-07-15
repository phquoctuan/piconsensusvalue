<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Response;
use Illuminate\Support\Str;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $posts = Post::all();
        // return $posts;

        $posts = Post::paginate(10);
        return $posts;

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
    public function create()
    {
        //
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
        //
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
        //
        if ((!$request->title) || (!$request->content)) {

            $response = Response::json([
                'error' => [
                    'message' => 'Please enter all required fields',
                ],
            ], 422);
            return $response;
        }

        $post = Post::find($id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->slug = Str::slug($request->title, '-');
        $post->save();

        $response = Response::json([
            'message' => 'The post has been updated.',
            'data' => $post,
        ], 200);

        return $response;
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
}
