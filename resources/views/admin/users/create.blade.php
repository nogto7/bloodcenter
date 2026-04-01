@extends('admin.layout')

@section('content')
<h2>{{ isset($user) ? 'Хэрэглэгч засах' : 'Шинэ хэрэглэгч' }}</h2>

<form action="{{ isset($user) ? route('admin.users.update',$user->id) : route('admin.users.store') }}" method="POST">
    @csrf
    @if(isset($user)) @method('PUT') @endif

    <div class="form-group">
        <label>Нэр</label>
        <input type="text" name="name" value="{{ $user->name ?? old('name') }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ $user->email ?? old('email') }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Role</label>
        <select name="role" class="form-control" id="roleSelect" required>
            <option value="admin" {{ (isset($user) && $user->role=='admin') ? 'selected' : '' }}>Admin</option>
            <option value="editor" {{ (isset($user) && $user->role=='editor') ? 'selected' : '' }}>Editor</option>
            <option value="publisher" {{ (isset($user) && $user->role=='publisher') ? 'selected' : '' }}>Publisher</option>
        </select>
    </div>
    
    <div class="form-group" id="departmentWrapper" style="{{ (isset($user) && $user->role=='publisher') ? '' : 'display:none;' }}">
        <label>Тохирох алба</label>
        <select name="department_id" class="form-control">
            <option value="">— Алба сонгох —</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ (isset($user) && $user->department_id==$dept->id) ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Нууц үг {{ isset($user) ? '(Шинэ бол оруулах)' : '' }}</label>
        <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
    </div>

    <div class="form-group">
        <label>Нууц үг баталгаажуулалт</label>
        <input type="password" name="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}>
    </div>

    <button type="submit" class="btn btn-primary mt-2">Хадгалах</button>
</form>
    
<script>
    const roleSelect = document.getElementById('roleSelect');
    const deptWrapper = document.getElementById('departmentWrapper');
    roleSelect.addEventListener('change', function(){
        if(this.value === 'publisher'){
            deptWrapper.style.display = 'block';
        } else {
            deptWrapper.style.display = 'none';
        }
    });
</script>
@endsection