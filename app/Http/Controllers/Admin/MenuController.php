<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    // List menus
    public function index()
    {
        // Parent-child structure
        $menus = Menu::whereNull('parent_id')
            ->where('active', 1)
            ->orderBy('sort')
            ->with('children') // Энэ нь recursive children load хийх болно
            // ->with(['children' => function ($q) {
            //     $q->orderBy('sort');
            // }])
            // ->paginate(10)
            ->get();
        $types = Menu::types();

        return view('admin.menus.index', compact('menus', 'types'));
    }

    // Show create form
    public function create()
    {
        // Parent menus select хийхэд хэрэг болно
        // $parents = Menu::whereNull('parent_id')->pluck('title', 'id');
        // return view('admin.menus.create', compact('parents'));
        $menus = Menu::where('active', 1)->orderBy('sort')->get(); // object collection

        $types = Menu::types();

        return view('admin.menus.create', compact('menus', 'types'));

    }

    // Save new menu
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'sort' => 'nullable|integer',
            'active' => 'boolean',
            'type' => 'nullable|required|string',
        ]);
        
        $data['user_id'] = Auth::id();

        $menus = Menu::create($data);

        return response()->json([
            'success' => true,
            'department' => $menus
        ]);

        // return redirect()->route('admin.menus.index')->with('success', 'Цэс нэмэгдлээ');
    }

    // Show edit form
    public function edit(Menu $menu)
    {
        // $parents = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->pluck('title','id');
        // return view('admin.menus.edit', compact('menu', 'parents'));

        $menus = Menu::where('active', 1)
            ->where('id', '!=', $menu->id) // өөрийгөө parent болгохоос сэргийлнэ
            ->orderBy('sort')
            ->with('children')
            ->get();

        $types = [
            'news'   => 'Мэдээ',
            'files'  => 'Файл',
            'page'   => 'Энгийн хуудас',
            'custom' => 'Тусгай layout',
        ];

        // dd($menus, $types);

        return view('admin.menus.edit', compact('menu', 'menus', 'types'));
    }

    // Update menu
    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'sort' => 'nullable|integer',
            'active' => 'boolean',
            'type' => 'required|in:news,files,page,custom',
        ]);

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu шинэчлэгдлээ');
    }

    // Delete menu
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('toast', [
                'type' => 'success',
                'message' => 'Цэс амжилттай устгалаа!'
            ]);
    }
}
