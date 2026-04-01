<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Slider;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // dd(Auth::check(), Auth::user());
        $sliderNews = Slider::where('active', 1)
            ->latest()
            ->get();

        $latestHighlight = News::where('highlight', 1)
            ->orderBy('created_at', 'desc')
            ->first();
            
        $highlightNews = News::where('highlight', 1)->where('is_active', 1)
            ->latest()
            ->first();

            $homeNews = News::where(function ($q) use ($latestHighlight) {
                $q->where('highlight', 0);
        
                if ($latestHighlight) {
                    $q->orWhere(function ($q2) use ($latestHighlight) {
                        $q2->where('highlight', 1)
                           ->where('id', '!=', $latestHighlight->id);
                    });
                }
            })
            ->orderBy('publish_at', 'desc')
            ->take(15)
            ->get();

        $slideVideo = Video::where('is_active', 1)->get();

        return view('index', compact('sliderNews', 'highlightNews', 'homeNews', 'slideVideo'));
    }
}
