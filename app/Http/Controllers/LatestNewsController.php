<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class LatestNewsController extends Controller
{
    public function index()
    {
        $latestNews = News::where('is_active', 1)->get();
        return view('index', compact('latestNews'));
    }
}
