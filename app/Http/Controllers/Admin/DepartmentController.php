<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        // Админ жагсаалтад идэвхгүйг нь ч харуулна, эс бөгөөс дахин удирдах боломжгүй болдог
        $departmentList = Department::withCount('employees')
            ->latest()
            ->paginate(20);
        $departments = Department::all();

        return view('admin.department.index', compact('departmentList', 'departments'));
    }

    public function create()
    {
        return view('admin.department.create');   
    }

    // ================= EMPLOYEE =================

    public function employee(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'fullname' => 'required|string',
            'position' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'order' => 'nullable|integer',
        ]);

        $data['order'] = $data['order'] ?? 0;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/employees'), $filename);
            $data['photo'] = 'uploads/employees/' . $filename;
        }

        $employee = Employee::create($data);

        return response()->json([
            'success' => true,
            'employee' => $employee
        ]);
    }

    // 🔹 Edit modal-д JSON авах
    public function employeeJson($id)
    {
        
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        return response()->json($employee);
    }

    // 🔹 Update
    public function employeeUpdate(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'fullname'      => 'required|string',
            'position'      => 'nullable|string',
            'phone'         => 'nullable|string',
            'email'         => 'nullable|email',
            'photo'         => 'nullable|image|max:4096',
            'order'         => 'nullable|integer',
        ]);

        if ($request->hasFile('photo')) {

            // хуучин зураг устгах
            if ($employee->photo && File::exists(public_path($employee->photo))) {
                File::delete(public_path($employee->photo));
            }

            $file = $request->file('photo');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/employees'), $filename);
            $data['photo'] = 'uploads/employees/'.$filename;
        }

        $employee->update($data);

        return response()->json([
            'success' => true,
            'employee' => $employee,
            'message' => 'Ажилчин амжилттай засагдлаа'
        ]);
    }

    // 🔹 Delete
    public function employeeDestroy(Employee $employee)
    {
        if ($employee->photo && File::exists(public_path($employee->photo))) {
            File::delete(public_path($employee->photo));
        }
        
        $employee->delete();
        return response()->json([
            'success' => true,
            'employee' => $employee,
            'message' => 'Ажилчин амжилттай засагдлаа'
        ]);
    }

    // Store
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'menu_id' => 'nullable|string',
            'description' => 'nullable',
            'color' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            // Кирилл, зайтай нэр URL эвддэг тул аюулгүй нэр үүсгэнэ
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/department'), $filename);
            $data['cover_image'] = 'uploads/department/' . $filename;
        }

        // Шууд идэвхтэй болгоно
        $data['is_active'] = $request->boolean('is_active');
        $data['menu_id'] = $request->menu_id ?? null;
        $department = Department::create($data);

        return response()->json([
            'success' => true,
            'department' => $department
        ]);

        // return redirect()->route('admin.department.create')->with('success', 'Мэдээ амжилттай хадгалагдлаа');
    }

    // Update
    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'name' => 'required',
            'menu_id' => 'nullable|string',
            'description' => 'nullable',
            'color' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('cover_image')) {

            // 🔥 Хуучин зургийг устгах
            if ($department->cover_image && File::exists(public_path($department->cover_image))) {
                File::delete(public_path($department->cover_image));
            }

            $file = $request->file('cover_image');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/department'), $filename);

            $data['cover_image'] = 'uploads/department/' . $filename;
        }
        
        // Edit form-д is_active талбар байхгүй тул засвар хийхэд идэвхтэй хэвээр үлдээнэ
        $data['is_active'] = 1;

        // menu_id-г устгах
        $data['menu_id'] = $request->menu_id ?? null;

        $department->update($data);

        
        return redirect()->route('admin.department.index')->with('success', 'Мэдээлэл амжилттай шинэчлэгдлээ');

        // return back()->with('success', 'Мэдээ амжилттай шинэчлэгдлээ');
    }

    public function upload(Request $request)
    {
        // TinyMCE-ийн images_upload_handler 'file' нэрээр илгээж, {location} хүлээдэг
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/ckeditor'), $filename);

            return response()->json([
                'location' => asset('uploads/ckeditor/' . $filename)
            ]);
        }

        return response()->json(['error' => 'No file'], 400);
    }

    public function json($id)
    {
        return Department::findOrFail($id);
    }

    public function edit(Department $department)
    {
        // 🔹 object collection авах
        $menus = Menu::where('active', 1)->orderBy('sort')->get();
        return view('admin.department.edit', compact('department', 'menus'));
    }

    public function destroy(Department $department)
    {
        if ($department->cover_image && File::exists(public_path($department->cover_image))) {
            File::delete(public_path($department->cover_image));
        }

        $department->delete();
        return back();
    }
}
