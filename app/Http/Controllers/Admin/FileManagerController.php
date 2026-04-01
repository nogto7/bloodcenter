<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Folder;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    public function index()
    {
        $folders = Folder::where(1)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort')
            ->get();
        return view('admin.files.index', compact('folders'));
    }

    public function create()
    {
        $folders = Folder::all();
        $menus   = Menu::where('active',1)
            ->with('children')
            ->orderBy('sort')
            ->get();

        return view('admin.files.create', compact('folders','menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:51200',
            'title' => 'required',
            'folder_id' => 'nullable',
            'menu_id' => 'nullable',
            'date' => 'nullable',
            'number' => 'nullable',
        ]);

        $uploaded = $request->file('file');
        $path = $uploaded->store('files', 'public');

        // 3️⃣ DB-д хадгалах
        $file = File::create([
            'title'     => $validated['title'],
            'path'      => $path,
            'mime_type' => $uploaded->getClientMimeType(),
            'size'      => $uploaded->getSize(),
            'folder_id' => $validated['folder_id'] ?? null,
            'menu_id'   => $validated['menu_id'] ?? null,
            'date'      => $validated['date'] ?? null,
            'number'    => $validated['number'] ?? null,
        ]);

        // 4️⃣ AJAX response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'file' => [
                    'id' => $file->id,
                    'title' => $file->title,
                    'path' => $file->path,
                    'mime_type' => $file->mime_type,
                    'size' => $file->size,
                    'created_at' => $file->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        }

        // 5️⃣ Энгийн submit
        return redirect()->route('admin.files.index');
    }

    public function show(File $file)
    {
        return view('admin.files.show', compact('file'));
    }

    public function destroy(File $file)
    {
        // 🔥 Storage дээр файл байвал устгана
        if ($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();
        return response()->json([
            'success' => true,
            'message' => 'Файл устгагдлаа',
            'file_id' => $file->id
        ]);
    }

    public function moveFile(Request $request, File $file)
    {
        $request->validate([
            'folder_id' => 'nullable|exists:folders,id'
        ]);

        $file->update([
            'folder_id' => $request->folder_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Файл амжилттай зөөгдлөө'
        ]);
    }
}

