<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\News;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminNewsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        if ($user->role === 'admin') {
            $newsList = News::with('user', 'department', 'editor', 'publisher')
                ->orderBy('publish_at', 'desc')
                ->latest()
                ->paginate(15);
        } elseif ($user->role === 'publisher') {
            $newsList = News::where('department_id', $user->department_id)
                // ->where('status','draft')
                ->with('user', 'department', 'editor', 'publisher')
                ->orderBy('publish_at','desc')
                ->paginate(15);
        } else { // editor
            $newsList = News::where('department_id', $user->department_id)
                ->with('user', 'department', 'editor', 'publisher')
                ->orderBy('publish_at','desc')
                ->paginate(20);
        }
    
        return view('admin.news.index', compact('newsList'));
    }

    public function create()
    {
        return view('admin.news.create');   
    }

    // Store
    public function store(Request $request, ImageService $imageService)
    {
        $user = Auth::user();

        $data = $request->validate([
            'title' => 'required',
            'excerpt' => 'nullable',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6144',
            'highlight_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            'menu_id' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($request->title);
        $data['publish_at'] = $data['publish_at'] ?? now();
        if($user->role == 'editor'){
            $data['status'] = 'draft';
            $data['is_active'] = 0;
        }else{
            $data['status'] = 'published';
            $data['is_active'] = 1;
        }

        if (empty(trim(strip_tags($request->content)))) {
            $data['content'] = null;
        }

        $data['highlight'] = $request->has('highlight') ? 1 : 0;
        $data['menu_id'] = $request->menu_id ?? null;
        $data['user_id'] = $user->id;
        $data['department_id'] = $user->department_id; // editor-ийн алба
        $data['status'] = 'pending'; // шинэ мэдээ pending

        // main image
        if ($request->hasFile('image')) {
            $data['image'] = $imageService->resizeAndSave($request->file('image'), 'uploads/news', 1200, 75);
        }

        // highlight image
        if ($request->hasFile('highlight_image')) {
            $data['highlight_image'] = $imageService->resizeAndSave($request->file('highlight_image'), 'uploads/news', 1200, 75);
        }

        $news = News::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Мэдээ амжилттай нэмэгдлээ',
            'data' => $news
        ]);
    }

    // Update
    public function update(Request $request, News $news, ImageService $imageService)
    {
        $data = $request->validate([
            'title' => 'required',
            'excerpt' => 'nullable',
            'content' => 'nullable|string',
            'image' => 'nullable|image',
            'highlight_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'is_active' => 'nullable|boolean',
            'highlight' => 'nullable|boolean',
            'publish_at' => 'nullable|date',
            'menu_id' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($request->title);

        $user = Auth::user();
        
        if($user->role === 'editor'){
            $data['is_active'] = 0;
        }else{
            $data['status'] = 'published';
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
        }

        if($data['is_active'] == 0){
            $data['status'] = 'pending';
        }
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['highlight'] = $request->has('highlight') ? 1 : 0;

        // menu_id-г устгах
        $data['menu_id'] = $request->menu_id ?? null;

        // ✅ logged-in user-ийн id-г нэмнэ
        $data['user_id'] = Auth::id();

        $user = Auth::user();

        if($user->role == 'editor' && $news->user_id != $user->id){
            abort(403);
        }
        
        if($user->role == 'editor'){
            $data['department_id'] = $user->department_id;
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $imageService->resizeAndSave(
                $request->file('image'),
                'uploads/news', // зөв folder
                1200,
                75
            );
        }
        
        if ($request->hasFile('highlight_image') && $request->file('highlight_image')->isValid()) {
        
            // Хуучин зургийг устгах
            if ($news->highlight_image && File::exists(public_path($news->highlight_image))) {
                File::delete(public_path($news->highlight_image));
            }
        
            $data['highlight_image'] = $imageService->resizeAndSave(
                $request->file('highlight_image'),
                'uploads/news',
                1200,
                75
            );
        }        
        // dd(
        //     $request->hasFile('highlight_image'),
        //     $request->file('highlight_image')
        // );

        $news->update($data);
        
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Мэдээ амжилттай шинэчлэгдлээ',
        //     'data' => $news
        // ]);

        return redirect()->route('admin.news.index')->with('success', 'Мэдээ амжилттай шинэчлэгдлээ');
    }

    public function publish(News $news)
    {
        $user = Auth::user();

        if ($news->status !== 'pending') {
            return back()->with('error','Энэ мэдээ аль хэдийн нийтлэгдсэн байна');
        }

        if (
            $user->role !== 'admin' &&
            ($user->role !== 'publisher' || $user->department_id !== $news->department_id)
        ) {
            abort(403);
        }

        $news->update([
            'status' => 'published',
            'is_active' => 1,
            'publish_at' => now(),
            'published_by' => Auth::id()
        ]);

        return back()->with('success','Мэдээ нийтлэгдлээ');
    }

    public function submit(News $news)
    {
        $user = Auth::user();

        if($user->role !== 'editor' || $news->user_id !== $user->id){
            abort(403);
        }

        $news->update([
            'status' => 'pending'
        ]);

        return back()->with('success','Мэдээ publisher руу илгээгдлээ');
    }

    // public function upload(Request $request)
    // {
    //     if ($request->hasFile('file')) { 
    //         $file = $request->file('file');

    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->move(public_path('uploads/news'), $filename);

    //         return response()->json([
    //             'location' => asset('uploads/news/' . $filename)
    //         ]);
    //     }

    //     return response()->json([
    //         'error' => 'File not uploaded'
    //     ], 400);
    // }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $filename = time() . '.jpg'; // 🔥 бүгдийг jpg болгоё

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);

            $image->scaleDown(1000);

            // ✅ 2. Encode + шахалт
            $encoded = $image->toJpeg(75); // 🔥 quality = 75 (сайн баланс)

            $path = 'uploads/news/' . $filename;

            Storage::disk('public')->put($path, (string) $encoded);

            return response()->json([
                'location' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'No file'], 400);
    }

    public function edit(News $news)
    {
        // 🔹 object collection авах
        $menus = Menu::where('active', 1)
                    ->orderBy('sort')
                    ->get(); // pluck биш get хэрэглэх

        return view('admin.news.edit', compact('news', 'menus'));
    }

    public function destroy(News $news)
    {
        if ($news->highlight_image && File::exists(public_path($news->highlight_image))) {
            File::delete(public_path($news->highlight_image));
        }

        $news->delete();
        return back();
    }
}
