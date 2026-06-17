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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $q = trim((string) $request->input('q', ''));
        $tab = $request->input('tab', 'all');

        $query = News::with('user', 'department', 'editor', 'publisher', 'menu');

        if ($user->role !== 'admin') {
            $query->where('department_id', $user->department_id);
        }

        if ($q !== '') {
            $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $q) . '%';
            $query->where(function ($w) use ($like) {
                $w->where('title', 'like', $like)
                  ->orWhere('excerpt', 'like', $like)
                  ->orWhere('content', 'like', $like);
            });
        }

        // Tab бүрийн тоог tab-аас бусад шүүлтүүр дээр тооцно
        $tabCounts = [
            'all' => (clone $query)->count(),
            'news' => (clone $query)->whereHas('menu', fn ($m) => $m->where('type', 'news'))->count(),
            'other' => (clone $query)->whereHas('menu', fn ($m) => $m->where('type', '!=', 'news'))->count(),
            'none' => (clone $query)->whereNull('menu_id')->count(),
        ];

        if ($tab === 'news') {
            $query->whereHas('menu', fn ($m) => $m->where('type', 'news'));
        } elseif ($tab === 'other') {
            $query->whereHas('menu', fn ($m) => $m->where('type', '!=', 'news'));
        } elseif ($tab === 'none') {
            $query->whereNull('menu_id');
        } else {
            $tab = 'all';
        }

        $perPage = $user->role === 'editor' ? 20 : 15;

        $newsList = $query->orderBy('publish_at', 'desc')
            ->latest()
            ->paginate($perPage)
            ->appends(['q' => $q, 'tab' => $tab]);

        return view('admin.news.index', compact('newsList', 'q', 'tab', 'tabCounts'));
    }

    public function create()
    {
        $menus = Menu::where('active', 1)
                    ->orderBy('sort')
                    ->get();

        return view('admin.news.create', compact('menus'));
    }

    /**
     * Гарчгаас давтагдахгүй slug үүсгэнэ. Аль хэдийн байгаа бол -2, -3 ... залгана.
     * Хоосон гарчигт цаг хугацаагаар fallback хийнэ.
     */
    protected function uniqueSlug(?string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug((string) $title);

        if ($base === '') {
            $base = 'medee-' . now()->format('YmdHis');
        }

        $slug = $base;
        $i = 2;

        while (
            News::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
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
            'publish_at' => 'nullable|date',
        ]);

        $data['slug'] = $this->uniqueSlug($request->title);
        $data['publish_at'] = !empty($data['publish_at']) ? $data['publish_at'] : now();

        // editor-ийн мэдээ хянагдахаар pending, admin/publisher-ийнх шууд нийтлэгдэнэ
        if($user->role == 'editor'){
            $data['status'] = 'pending';
            $data['is_active'] = 0;
        }else{
            $data['status'] = 'published';
            $data['is_active'] = 1;
        }

        if (empty(trim(strip_tags($request->content)))) {
            $data['content'] = null;
        }

        $data['highlight'] = $request->has('highlight') ? 1 : 0;
        $data['menu_id'] = $request->menu_id ?: null; // хоосон мөрийг null болгоно (bigint багана)
        $data['user_id'] = $user->id;
        $data['department_id'] = $user->department_id; // editor-ийн алба

        // main image
        if ($request->hasFile('image')) {
            $data['image'] = $imageService->resizeAndSave($request->file('image'), 'uploads/news', 1200, 75);
        }

        // highlight image
        if ($request->hasFile('highlight_image')) {
            $data['highlight_image'] = $imageService->resizeAndSave($request->file('highlight_image'), 'uploads/news', 1200, 75);
        }

        $news = News::create($data);

        // Модал (AJAX) JSON хүлээдэг, create хуудасны энгийн form redirect хүлээдэг
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Мэдээ амжилттай нэмэгдлээ',
                'data' => $news
            ]);
        }

        return redirect()->route('admin.news.index')->with('success', 'Мэдээ амжилттай нэмэгдлээ');
    }

    // Update
    public function update(Request $request, News $news, ImageService $imageService)
    {
        $user = Auth::user();

        if($user->role == 'editor' && $news->user_id != $user->id){
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required',
            'excerpt' => 'nullable',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:6144',
            'highlight_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:8192',
            'is_active' => 'nullable|boolean',
            'highlight' => 'nullable|boolean',
            'publish_at' => 'nullable|date',
            'menu_id' => 'nullable|string',
        ]);

        $data['slug'] = $this->uniqueSlug($request->title, $news->id);

        // editor-ийн засвар дахин хянагдана, бусдынх checkbox-оор шийдэгдэнэ
        $data['is_active'] = $user->role === 'editor' ? 0 : ($request->has('is_active') ? 1 : 0);
        $data['status'] = $data['is_active'] ? 'published' : 'pending';
        $data['highlight'] = $request->has('highlight') ? 1 : 0;
        $data['menu_id'] = $request->menu_id ?: null; // хоосон мөрийг null болгоно (bigint багана)

        // Огноог хөндөөгүй бол хуучин утгыг хадгална
        if (empty($data['publish_at'])) {
            unset($data['publish_at']);
        }

        if($user->role == 'editor'){
            $data['department_id'] = $user->department_id;
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($news->image && File::exists(public_path($news->image))) {
                File::delete(public_path($news->image));
            }

            $data['image'] = $imageService->resizeAndSave(
                $request->file('image'),
                'uploads/news',
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

        $news->update($data);

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

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $filename = time() . '_' . uniqid() . '.jpg'; // бүгдийг jpg болгоно, нэр давхцахаас сэргийлнэ

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

        if ($news->image && File::exists(public_path($news->image))) {
            File::delete(public_path($news->image));
        }

        $news->delete();
        return back()->with('success', 'Мэдээ устгагдлаа');
    }
}
