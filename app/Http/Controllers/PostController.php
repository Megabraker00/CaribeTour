<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class PostController extends Controller
{
    public function index() 
    {
        $blogs = Blog::all();

        return view('blogs', ['blogs' => $blogs]);
    }

    public function show($postSlug)
    {
        $post = Blog::where('slug', $postSlug)->first();

        return view('blog_post', ['post' => $post]);
    }
}
