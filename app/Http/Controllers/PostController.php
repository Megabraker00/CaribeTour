<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Status;

class PostController extends Controller
{
    public function index()
    {
        $blogs = Blog::query()
            ->with(['images', 'createdUser'])
            ->where('status_id', Status::BLOG_PUBLISHED)
            ->orderByDesc('id')
            ->get();

        return view('blogs', ['blogs' => $blogs]);
    }

    public function show(string $postSlug)
    {
        $post = Blog::query()
            ->with(['images', 'createdUser'])
            ->where('slug', $postSlug)
            ->where('status_id', Status::BLOG_PUBLISHED)
            ->firstOrFail();

        return view('blog_post', ['post' => $post]);
    }
}
