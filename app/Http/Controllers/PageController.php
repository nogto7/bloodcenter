<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\File;
use App\Models\Menu;
use App\Models\News;
use App\Models\Section;

class PageController extends Controller
{
    public function menu($slug)
    {
        $menu = Menu::where('url', $slug)->where('active', 1)->first();

        // dd($menu);

        if (!$menu) {
            // Магадгүй slug нь sub menu бол parent-ийн url нэмж шалгах
            $parts = explode('/', $slug); // legal/government -> ['legal','government']
    
            if(count($parts) == 2){
                $parentSlug = $parts[0];
                $childSlug  = $parts[1];
    
                $menu = Menu::where('url', $childSlug)
                            ->whereHas('parent', function($q) use ($parentSlug) {
                                $q->where('url', $parentSlug);
                            })
                            ->firstOrFail();
            }
        }
        // dd($menu);

        // Default null
        $files = collect();

        // ⛔ MENU ОЛДООГҮЙ БОЛ ЭНД ДУУСНА
        if (!$menu) {
            abort(404);
        }

        $parentMenu = $menu->parent;

        switch ($menu->type) {

            case 'news':
                $news = News::where('menu_id', $menu->id)
                            ->where('is_active', 1)
                            ->orderBy('publish_at','desc')
                            ->paginate(10);

                return view('news.index', compact('menu','news'));

            case 'files':
                $files = File::where('menu_id', $menu->id)
                            ->latest()
                            ->get();

                return view('files.index', compact('menu', 'parentMenu', 'files'));

            case 'page':
                $menu->load([
                    'groups.items.file' // 🔥 FileManager холбоо
                ]);
                // $menu->load('groups.items.file');
                
                $department = Department::with(['employees' => function($q){
                    $q->orderBy('order');
                }])
                ->where('menu_id', $menu->id)
                ->where('is_active', 1)
                ->latest()
                ->first();
                return view('pages.show', compact('menu', 'department'));

            case 'custom':
                abort_if(!view()->exists('pages.custom.'.$menu->url), 404);
                return view('pages.custom.'.$menu->url, compact('menu'));

            default:
                abort(404);
        }

    }

    public function fileShow($id)
    {
        $file = File::with('menu.parent')->findOrFail($id);

        $file->increment('views');

        $menu = $file->menu;
        $parentMenu = $menu?->parent;

        return view('files.show', compact('file', 'menu', 'parentMenu'));
    }

    public function departmentShow($id)
    {
        $department = Department::with('menu', 'employees')
            ->where('is_active', 1)
            ->findOrFail($id);

        $menu = $department->menu;

        return view('departments.show', compact(
            'department',
            'menu'
        ));
    }
}

