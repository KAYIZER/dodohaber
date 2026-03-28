<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;

class FrontendController extends BaseController
{

    public function index()
    {
        $featuredPosts = Post::published()
            ->featured()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(20)
            ->get();
            
        $narrowFeaturedPosts = Post::published()
            ->narrowFeatured()
            ->with(['category'])
            ->latest('published_at')
            ->take(5)
            ->get();
            
        $breakingNews = Post::published()
            ->breaking()
            ->latest('published_at')
            ->take(5)
            ->get();

        $latestPosts = Post::published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->paginate(12);

        return view('frontend.home', compact('featuredPosts', 'narrowFeaturedPosts', 'breakingNews', 'latestPosts'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['author'])
            ->latest('published_at')
            ->paginate(15);

        return view('frontend.category', compact('category', 'posts'));
    }

    public function post($slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->with(['category', 'author'])
            ->firstOrFail();

        // Sayfa görüntülenme sayısını artır (session bazlı tekrar engelleme)
        $viewedKey = 'viewed_posts.' . $post->id;
        if (!session()->has($viewedKey)) {
            $post->increment('view_count');
            session()->put($viewedKey, true);
        }

        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('frontend.post', compact('post', 'relatedPosts'));
    }
}
