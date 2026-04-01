<?php

// app/Http/Controllers/Admin/AdminUserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index() {
        $users = User::where('id', '<>', Auth::id())->get();
        $departments = Department::all(); // бүх албыг татаж байна
        return view('admin.users.index', compact('users', 'departments'));
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email',
            'role' => ['required', Rule::in(['admin','editor','publisher'])],
            'department_id' => [
                'nullable',
                'required_if:role,publisher',
                'exists:departments,id'
            ],
            'password' => 'required|min:3|confirmed',
        ]);

        // $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // AJAX хүсэлт бол JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Хэрэглэгч амжилттай нэмэгдлээ',
                'data' => $user
            ]);
        }

        return redirect()->back()->with('success', 'Хэрэглэгч амжилттай нэмэгдлээ');
    }

    public function edit(User $user) {
        $departments = Department::all(); // бүх албыг татаж байна
        return view('admin.users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email,'.$user->id,
            'role' => ['required', Rule::in(['admin','editor','publisher'])],
            'password' => 'nullable|min:6|confirmed',
        ]);
    
        $data = $request->only('name','email','role','department_id');
    
        if($request->password){
            $data['password'] = Hash::make($request->password); // ✅ hash хийж байна
        }
    
        $user->update($data);
    
        return redirect()->route('admin.users.index')->with('success', 'Хэрэглэгч амжилттай шинэчиллээ.');
    }

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Хэрэглэгч устгагдлаа.');
    }
}