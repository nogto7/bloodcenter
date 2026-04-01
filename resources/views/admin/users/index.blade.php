@extends('admin.layout')

@section('content')
<div class="admin_root folder_header">
    <div class="dcsb">
        <div class="n_breadcrumb dfc">
            <ul>
                <li><a href=""><span class="bread_home"></span>Эхлэл</a></li>
                <li><p>Хэрэглэгчид</p></li>
            </ul>
        </div>
        <div class="fline_header dfc">
            <div class="file_options dfc">
                <button class="f_f_button f_edit" id="btnRename" disabled><span></span>Засах</button>
                <button class="f_f_button f_delete" id="btnDelete" disabled><span></span>Устгах</button>
            </div>
            <div class="folder_file dfc">
                <button class="f_f_button f_file" data-bs-toggle="modal" data-bs-target="#addUser"><span></span>Шинэ хэрэглэгч</button>
            </div>
        </div>
    </div>
</div>
<div class="admin_file_wrap">
    <div class="file_content">
        <h2>Хэрэглэгч</h2>
        <div class="table_wrap">
            <table class="table_content">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Нэр</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $item)
                    <tr>
                        <td style="width: 40px">{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->role }}</td>
                        <td style="width: 160px">
                            <div class="dfc">
                                <a href="{{ route('admin.users.edit', $item->id) }}" class="f_f_button f_edit"><span></span>Засах</a>
                                <form action="{{ route('admin.users.destroy', $item) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="f_f_button f_delete ml1" onclick="return confirm('Та устгахдаа итгэлтэй байна уу?')"><span></span>Устгах</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>{{ isset($user) ? 'Хэрэглэгч засах' : 'Шинэ хэрэглэгч' }}</h2>
            </div>
            <form id="userForm" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal_main">
                    <div class="form_item">
                        <label class="form_label">Нэр</label>
                        <input type="text" name="name" class="form_input" required>
                    </div>
                
                    <div class="form_item">
                        <label class="form_label">И-мэйл</label>
                        <input type="email" name="email" class="form_input" required>
                    </div>
                
                    <div class="form_item">
                        <label class="form_label">Дүр</label>
                        <select name="role" class="form_select" id="roleSelect" required>
                            <option value="admin" {{ (isset($user) && $user->role=='admin') ? 'selected' : '' }}>Админ</option>
                            <option value="editor" {{ (isset($user) && $user->role=='editor') ? 'selected' : '' }}>Нийтлэлч</option>
                            <option value="publisher" {{ (isset($user) && $user->role=='publisher') ? 'selected' : '' }}>Редактор</option>
                        </select>
                    </div>
                    
                    <div class="form_item" id="departmentWrapper" style="{{ (isset($user) && $user->role=='publisher') ? '' : 'display:none;' }}">
                        <label class="form_label">Тохирох алба</label>
                        <select name="department_id" class="form_select">
                            <option value="">— Алба сонгох —</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ (isset($user) && $user->department_id==$dept->id) ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                
                    <div class="form_item">
                        <label class="form_label">Нууц үг</label>
                        <input type="password" name="password" class="form_input" required>
                    </div>
                
                    <div class="form_item">
                        <label class="form_label">Нууц үг баталгаажуулалт</label>
                        <input type="password" name="password_confirmation" class="form_input" required>
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal"
                        aria-label="Close">Болих</button>
                        <button type="submit" class="__btn btn_primary ml2">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
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
