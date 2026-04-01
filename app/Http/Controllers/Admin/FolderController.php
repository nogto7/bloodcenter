<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;

class FolderController extends Controller
{
    public function index()
    {
        $folders = Folder::whereNull('parent_id')
            ->orderBy('sort')
            ->with('children')
            ->get();

        return view('admin.folders.index', compact('folders'));
    }

    public function create()
    {
        $folders = Folder::whereNull('parent_id')->get();
        return view('admin.folders.create', compact('folders'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'parent_id' => 'nullable|exists:folders,id'
        // ]);

        $validated = $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:folders,id'
        ]);
    
        $folder = Folder::create($validated);
    
        // 🔥 AJAX байвал JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'folder' => $folder
            ]);
        }

        // Folder::create($request->only('name','parent_id'));

        // return redirect()->route('admin.folders.index');
    }

    public function rename(Request $request, Folder $folder)
    {
        $folder->update(['name' => $request->name]);
        return response()->json(['success' => true]);
    }

    public function files(Folder $folder)
    {
        return response()->json([
            'folder' => ['id' => $folder->id, 'name' => $folder->name],
            'files' => $folder->files()->with([
                'folder:id,name',
                'menu:id,title'
            ])->select('id','title','path','mime_type','created_at','size')->get(),
        ]);
    }

    public function move(Request $request, Folder $folder)
    {
        $request->validate([
            'parent_id' => 'nullable|exists:folders,id'
        ]);

        // Өөрийнх нь child дотор хийхээс хамгаална
        if ($request->parent_id == $folder->id) {
            return response()->json(['error' => 'Invalid move'], 422);
        }

        $folder->update([
            'parent_id' => $request->parent_id
        ]);

        return response()->json(['success' => true]);
    }

    // public function destroy(Folder $folder, Request $request)
    // {
    //     // dd($folder);
    //     // $folder->delete(); // children cascade
        
    //     // // AJAX байвал JSON response буцаана
    //     // if ($request->ajax()) {
    //     //     return response()->json([
    //     //         'success' => true,
    //     //         'folder_id' => $folder->id,
    //     //         'message' => 'Folder амжилттай устгагдлаа'
    //     //     ]);
    //     // }

    //     // return back()->with('success', 'Folder устгагдлаа');
    //     // try {
    //     //     $folder->delete();
    
    //     //     return response()->json([
    //     //         'success' => true
    //     //     ]);
    //     // } catch (\Throwable $e) {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => $e->getMessage()
    //     //     ], 500);
    //     // }
    // }

    public function destroy(Folder $folder)
    {
        // child folder-уудыг эхлээд устгана
        $folder->children()->get()->each(function ($child) {
            $child->files()->delete();
            $child->delete();
        });

        // өөрийн файлууд
        $folder->files()->delete();

        // өөрийг нь
        $folder->delete();

        return response()->json(['success' => true]);
    }


}
