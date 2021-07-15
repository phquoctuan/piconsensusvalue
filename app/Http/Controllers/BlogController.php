<?php

namespace App\Http\Controllers;

use App\Post;
class BlogController extends Controller
{
    //
    public function index()
    {
        $posts = Post::paginate(5); //Post::all();
        return view('blog.index', compact('posts'));
    }
}
