<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupItem;
use App\Models\Menu;
use App\Models\File;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['items.file'])->orderBy('sort')->get();
        $groupName = Group::all();

        $types = [
            'text'   => 'Текст',
            'link'   => 'Холбоос',
            'image'  => 'Зураг',
            'file'   => 'Файл'
        ];

        return view('admin.groups.index', compact('groups', 'groupName', 'types'));
    }

    // ================= GROUP =================

    public function store(Request $request)
    {
        $lastSort = Group::where('menu_id', $request->menu_id)->max('sort');

        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'title'   => 'required',
        ]);

        $validated['sort'] = ($lastSort ?? 0) + 1;

        $group = Group::create($validated);

        return response()->json([
            'success' => true,
            'data' => $group
        ]);
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'title'   => 'required',
            'sort'    => 'nullable|integer'
        ]);

        $group->update($validated);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()->back()->with('success', true);
    }

    public function getGroup($id)
    {
        return response()->json(
            Group::findOrFail($id)
        );
    }

    // ================= ITEMS =================

    public function storeItem(Request $request)
    {
        $lastSort = GroupItem::where('group_id', $request->group_id)->max('sort');

        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'type'     => 'required|in:text,link,image,file',
            'title'    => 'nullable|string',
            'date'     => 'nullable|date',
            'content'  => 'nullable',
            'file_id'  => 'nullable|exists:files,id',
            'link'     => 'nullable|string'
        ]);

        $validated['sort'] = ($lastSort ?? 0) + 1;

        $item = GroupItem::create($validated);

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    public function updateItem(Request $request, GroupItem $groupItem)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'type'     => 'required|in:text,link,image,file',
            'title'    => 'nullable|string',
            'date'     => 'nullable|date',
            'content'  => 'nullable',
            'file_id'  => 'nullable|exists:files,id',
            'link'     => 'nullable|string'
        ]);

        if ($request->type !== 'link') {
            $validated['link'] = null;
        }

        $groupItem->update($validated);

        return response()->json([
            'success' => true
        ]);
    }

    public function getItem(GroupItem $groupItem)
    {
        return response()->json(
            $groupItem->load('file')
        );
    }

    public function destroyItem(GroupItem $groupItem)
    {
        $groupItem->delete();

        return redirect()->back()->with('success', true);
    }

    // ================= SORT =================

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