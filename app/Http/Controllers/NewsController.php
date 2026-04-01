<?php

namespace App\Http\Controllers;
use App\Models\News;

use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('is_active', true)
            ->where('status', 'published') // 👈 ЭНЭ ЧУХАЛ
            ->where('publish_at', '<=', now()) // (optional - future post block)
            ->orderBy('publish_at', 'desc')
            ->paginate(15);

        return view('news.index', compact('news'));
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('news.detail', compact('news'));
    }
}

