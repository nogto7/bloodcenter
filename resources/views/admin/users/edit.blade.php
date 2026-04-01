@extends('admin.layout')

@section('content')
<h2>Хэрэглэгч засах</h2>

<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div>
        <label>Нэвтрэх нэр</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
    </div>
    
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
    </div>
    
    <div>
        <label>Role</label>
        <select name="role" required>
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="editor" {{ $user->role == 'editor' ? 'selected' : '' }}>Editor</option>
            <option value="publisher" {{ $user->role == 'publisher' ? 'selected' : '' }}>Publisher</option>
        </select>
    </div>
    
    <div>
        <label>Department</label>
        <select name="department_id">
            <option value="">— Алба сонгох —</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
    </div>
    
    <div>
        <label>Нууц үг шинэчлэх</label>
        <input type="password" name="password">
        <small>Нууц үгийг өөрчилнө</small>
    </div>
    
    <button type="submit">Хадгалах</button>
</form>
@endsection