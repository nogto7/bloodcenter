<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupItem;
use App\Models\File;
use Illuminate\Http\Request;

class GroupItemController extends Controller
{
    public function create(Group $group)
    {
        $files = File::latest()->get(); // FileManager-с
        return view('admin.group-items.create', compact('group','files'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'type'     => 'required|in:text,link,image,file',
            'title'    => 'nullable|string',
            'date'     => 'nullable|date',
            'content'  => 'nullable',
            'file_id'  => 'nullable|exists:files,id',
        ]);

        GroupItem::create($validated);

        return redirect()->back()->with('success', 'Амжилттай нэмэгдлээ');
    }

    public function edit(GroupItem $groupItem)
    {
        $files = File::latest()->get();
        return view('admin.group-items.edit', compact('groupItem','files'));
    }

    public function update(Request $request, GroupItem $groupItem)
    {
        $validated = $request->validate([
            'type'     => 'required|in:text,link,image,file',
            'title'    => 'nullable|string',
            'date'     => 'nullable|date',
            'content'  => 'nullable',
            'file_id'  => 'nullable|exists:files,id',
        ]);

        $groupItem->update($validated);

        return redirect()->back()->with('success', 'Шинэчлэгдлээ');
    }

    public function destroy(GroupItem $groupItem)
    {
        $groupItem->delete();

        return response()->json([
            'success' => true
        ]);
    }

    // 🔥 Drag & Drop sort
    public function sort(Request $request)
    {
        foreach ($request->items as $index => $id) {
            GroupItem::where('id', $id)->update([
                'sort' => $index
            ]);
        }

        return response()->json(['success' => true]);
    }
}