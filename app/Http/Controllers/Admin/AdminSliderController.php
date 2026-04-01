<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminSliderController extends Controller
{
    public function index()
    {
        $sliderList = Slider::latest()
            ->orderBy('created_at', 'desc')
            ->latest()
            ->paginate(15);
        return view('admin.slider.index', compact('sliderList'));
    }

    public function create()
    {
        return view('admin.slider.create');   
    }

    // Store
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable',
            'url' => 'nullable',
            'sort' => 'nullable|integer',
            'desc' => 'nullable',
            'highlight_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('highlight_image')) {
            $file = $request->file('highlight_image');
            $filename = time().'_highlight_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/slider'), $filename);
            $data['highlight_image'] = 'uploads/slider/' . $filename;
        }

        // $data['publish_at'] = $data['publish_at'] ?? now();

        // Шууд идэвхтэй болгоно
        $data['active'] = 1;

        $slider = Slider::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Мэдээлэл амжилттай нэмэгдлээ',
            'data' => $slider
        ]);

        // return redirect()->route('admin.slider.create')->with('success', 'Мэдээ амжилттай хадгалагдлаа');
    }

    // Update
    public function update(Request $request, Slider $slider)
    {
        $data = $request->validate([
            'title' => 'nullable',
            'url' => 'nullable',
            'sort' => 'nullable|integer',
            'desc' => 'nullable',
            'highlight_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('highlight_image')) {

            // 🔥 Хуучин зургийг устгах
            if ($slider->highlight_image && File::exists(public_path($slider->highlight_image))) {
                File::delete(public_path($slider->highlight_image));
            }

            $file = $request->file('highlight_image');
            $filename = time().'_highlight_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/slider'), $filename);

            $data['highlight_image'] = 'uploads/slider/' . $filename;
        }

        $slider->update($data);

        return back()->with('success', 'Мэдээ амжилттай шинэчлэгдлээ');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {

            $file = $request->file('upload');
            $filename = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/slider'), $filename);

            return response()->json([
                "uploaded" => true,
                "url" => asset('uploads/slider/'.$filename)
            ]);
        }

        return response()->json([
            "uploaded" => false,
            "error" => [
                "message" => "File not uploaded"
            ]
        ]);
    }

    public function edit(Slider $slider)
    {
        return view('admin.slider.edit', compact('slider'));
    }

    public function destroy(Slider $slider)
    {
        // 🔥 Зургийг устгах
        if ($slider->highlight_image && File::exists(public_path($slider->highlight_image))) {
            File::delete(public_path($slider->highlight_image));
        }

        $slider->delete();

        return back()->with('success', 'Мэдээ амжилттай устгагдлаа');
    }
}
