<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVideoController extends Controller
{
    public function index()
    {
        $videoList = Video::paginate(15);
        return view('admin.video.index', compact('videoList'));
    }

    public function create()
    {
        return view('admin.video.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'url' => 'required',
            'is_active' => 'boolean',
        ]);
    
        $video = Video::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Мэдээ амжилттай нэмэгдлээ',
            'data' => $video
        ]);
    }

    public function edit(Video $video)
    {
        return view('admin.video.edit', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $data = $request->validate([
            'title' => 'required',
            'url' => 'required',
            'is_active' => 'boolean'
        ]);

        $video->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Амжилттай засагдлаа',
            'data' => $video
        ]);
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
