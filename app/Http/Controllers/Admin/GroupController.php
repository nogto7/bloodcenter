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
        // $groups = Group::with('menu')->orderBy('sort')->get();
        $groups = Group::with(['items.file'])->get();
        $groupName = Group::all();

        $types = [
            'text'   => 'Текст',
            'link'  => 'Холбоос',
            'image'  => 'Зураг',
            'file'    => 'Файл'
        ];

        return view('admin.groups.index', compact('groups', 'groupName', 'types'));
    }

    public function create()
    {
        $menus = Menu::where('active',1)->get();
        return view('admin.groups.create', compact('menus'));
    }

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
            'message' => 'Бүлэг амжилттай нэмэгдлээ',
            'data' => $group
        ]);

        // return redirect()->route('admin.groups.index')
        //     ->with('success', 'Бүлэг нэмэгдлээ');
    }

    public function edit(Group $group)
    {
        $menus = Menu::where('active',1)->get();
        return view('admin.groups.edit', compact('group','menus'));
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'title'   => 'required',
            'sort'    => 'nullable|integer'
        ]);

        $group->update($validated);

        return redirect()->route('admin.groups.index')
            ->with('success', 'Амжилттай засагдлаа');
    }

    public function destroy(Group $group)
    {
        $group->delete();

        return response()->json([
            'success' => true
        ]);
    }

    // Group items
    public function createItem(Group $group)
    {
        $files = File::latest()->get(); // FileManager-с
        return view('admin.group-items.create', compact('group','files'));
    }

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

        GroupItem::create($validated);

        return redirect()->back()->with('success', 'Амжилттай нэмэгдлээ');
    }

    public function editItem(GroupItem $groupItem)
    {
        $files = File::latest()->get();
        return view('admin.group-items.edit', compact('groupItem','files'));
    }

    public function updateItem(Request $request, GroupItem $groupItem)
    {
        $validated = $request->validate([
            'type'     => 'required|in:text,link,image,file',
            'title'    => 'nullable|string',
            'date'     => 'nullable|date',
            'content'  => 'nullable',
            'file_id'  => 'nullable|exists:files,id',
            'link'     => 'nullable|string'
        ]);

        // 🔥 type != link бол link-г null болгохгүй
        if ($request->type !== 'link') {
            unset($validated['link']);
        }

        $groupItem->update($validated);

        return redirect()->back()->with('success', 'Шинэчлэгдлээ');
    }

    public function getItem($id)
    {
        return response()->json(
            GroupItem::findOrFail($id)
        );
    }

    public function getGroup($id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        return response()->json($group);
    }

    public function showItem($groupItem)
    {
        $items = GroupItem::find($groupItem);

        if (!$items) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        return response()->json($items);
    }

    public function destroyItem(GroupItem $groupItem)
    {
        $groupItem->delete();

        return response()->json([
            'success' => true,
            'item' => $groupItem,
            'message' => 'Ажилчин амжилттай засагдлаа'
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