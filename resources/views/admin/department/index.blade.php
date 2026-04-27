@extends('admin.layout')
@section('content')
<div class="admin_root folder_header">
    <div class="dcsb">
        <div class="n_breadcrumb dfc">
            <ul>
                <li><a href=""><span class="bread_home"></span>Эхлэл</a></li>
                <li><p>Албадын жагсаалт</p></li>
            </ul>
        </div>
        <div class="fline_header dfc">
            <div class="file_options dfc">
                <button class="f_f_button f_delete" id="btnDelete" disabled><span></span>Устгах</button>
            </div>
            <div class="folder_file dfc">
                <button class="f_f_button f_file" data-bs-toggle="modal" data-bs-target="#addDepartment"><span></span>Алба нэмэх</button>
                <button class="f_f_button f_file ml1" data-bs-toggle="modal" data-bs-target="#addEmployee"><span></span>Ажилчид нэмэх</button>
            </div>
        </div>
    </div>
</div>
<div class="admin_file_wrap">
    <div class="file_content">
        <h2>Алба</h2>
        <div class="table_wrap">
            <table class="table_content">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Зураг</th>
                        <th>Гарчиг</th>
                        <th>Агуулга</th>
                        <th>Үүсгэсэн огноо</th>
                        <th>Нийт ажилчид</th>
                        <th>Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departmentList as $key => $item)
                    <tr>
                        <td style="width: 40px">{{ $departmentList->firstItem() + $key }}</td>
                        <td>
                            @if($item->cover_image)
                            <div class="img_block"><img src="/{{ $item->cover_image }}" alt=""></div>
                            @else
                            <div class="img_block"><img src="/images/not_image.png" alt=""></div>
                            @endif
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>{{ strip_tags(Str::limit($item->content, 240)) }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <div class="dfc">{{ $item->employees_count }} @if( $item->color ) <span class="department_color" style="background: {{ $item->color }}"></span> @endif</div>
                        </td>
                        <td>
                            <div class="dfc">
                                <a href="{{ route('admin.department.edit', $item->id) }}" class="f_f_button f_edit"><span></span>Засах</a>
                                {{-- <button 
                                    class="f_f_button f_edit edit-btn department_edit"
                                    data-id="{{ $item->id }}">
                                    <span></span>Засах
                                </button> --}}
                                <form action="{{ route('admin.department.destroy', $item) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="f_f_button f_delete ml1" onclick="return confirm('Та устгахдаа итгэлтэй байна уу?')"><span></span>Устгах</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    
                    {{-- Ажилчдын нэрийг жагсаах --}}
                    @if($item->employees->isNotEmpty())
                    <tr>
                        <td colspan="7">
                            <ul class="ad_emp_list">
                                @foreach($item->employees as $emp)
                                    <li>
                                        <div class="img_block"><img src="{{ asset($emp->photo) }}" alt="{{ $emp->fullname }}"></div>
                                        <div class="emp_desc">
                                            <h3>{{ $emp->fullname }}</h3>
                                            <p>{{ $emp->position }}</p>
                                            <div class="emp_other">
                                                <b>{{ $emp->phone }}</b>
                                                <span>{{ $emp->email }}</span>
                                            </div>
                                        </div>
                                        <div class="dfc mt1">
                                            <button 
                                                type="button"
                                                class="f_f_button f_edit edit-employee-btn" 
                                                data-id="{{ $emp->id }}"><span></span>
                                            </button>
                                            <form action="{{ route('admin.employee.destroy', $emp) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="f_f_button f_delete ml1" onclick="return confirm('Та устгахдаа итгэлтэй байна уу?')">
                                                    <span></span>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="7"><span>Ажилчин байхгүй</span></td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="7">Алба олдсонгүй</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addDepartment" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog other_modal" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Алба нэмэх</h2>
            </div>
            <form id="departmentForm">
                @csrf
                <div class="modal_main">
                    <div class="dg g3 gap1">
                        <div class="form_item col2to1">
                            <label class="form_label">Алба</label>
                            <input type="text" name="name" class="form_input" placeholder="Албаны нэр">
                        </div>
                        <div class="form_item">
                            <label class="form_label">Хамаарах цэс</label>
                            <select name="menu_id" class="form_select">
                                <option value="">— Menu-д холбох —</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                    @foreach($menu->children as $child)
                                        <option value="{{ $child->id }}">— {{ $child->title }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Өнгө сонгох</label>
                        <input type="color" name="color" />
                    </div>
                    <div class="form_item">
                        <label class="form_label">Ерөнхий мэдээлэл</label>
                        <textarea name="description" id="content"></textarea>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Онцлох зураг</label>
                        <input type="file" name="cover_image" class="form_input">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Дараалал</label>
                        <input type="text" name="order" class="form_input" placeholder="Дараалал">
                    </div>
                    <div class="form_item">
                        <div class="dfc">
                            <label class="form-checkbox-label">
                                <input type="checkbox" name="is_active" value="1" class="form-checkbox hidden" checked>
                                <span class="form-checkmark"></span>
                                <span class="label-text">Нийтлэх</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal"
                        aria-label="Close">Болих</button>
                        <button type="submit" onclick="closeModal('departmentModal')" class="__btn btn_primary ml2" data-bs-dismiss="modal"
                        aria-label="Close">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addEmployee" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Ажилчин</h2>
            </div>
            <form id="employeeForm">
                @csrf
                <div class="modal_main">
                    <div class="form_item">
                        <label class="form_label">Алба</label>
                        <select name="department_id" class="form_select">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">
                                    {{ $dept->name }} ({{ $dept->menu->title }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Овог нэр</label>
                        <input type="text" name="fullname" class="form_input" placeholder="Овог нэр">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Албан тушаал</label>
                        <input type="text" name="position" class="form_input" placeholder="Албан тушаал">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Зураг</label>
                        <input type="file" name="photo" id="photo" class="form_input hidden" hidden>

                        <div class="upload_box" onclick="document.getElementById('photo').click()">
                            <img id="imagePreview" src="/images/icon_upload_file.png" alt="">
                            <p>Зураг сонгох</p>
                        </div>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Утасны дугаар</label>
                        <input type="text" name="phone" class="form_input" placeholder="Утасны дугаар">
                    </div>
                    <div class="form_item">
                        <label class="form_label">И-мэйл хаяг</label>
                        <input type="email" name="email" class="form_input" placeholder="И-мэйл хаяг">
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal"
                        aria-label="Close">Болих</button>
                        <button type="submit" onclick="closeModal('departmentModal')" class="__btn btn_primary ml2" data-bs-dismiss="modal"
                        aria-label="Close">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editEmployeeModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Ажилчин засах</h2>
            </div>
            <form id="editEmployeeForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_emp_id">
                <div class="modal_main">
                    <div class="form_item">
                        <label class="form_label">Алба</label>
                        <select name="department_id" id="edit_department_id" class="form_select">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->menu->title }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Овог нэр</label>
                        <input type="text" name="fullname" id="edit_fullname" class="form_input">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Албан тушаал</label>
                        <input type="text" name="position" id="edit_position" class="form_input">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Зураг</label>
                        <input type="file" name="photo" id="edit_emp_photo" class="form_input hidden" accept="image/*">
                        <div class="upload_box" onclick="document.getElementById('edit_emp_photo').click()">
                            <img id="editEmpImagePreview" src="/images/icon_upload_file.png" alt="preview">
                            <p>Зураг солих</p>
                        </div>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Утасны дугаар</label>
                        <input type="text" name="phone" id="edit_phone" class="form_input">
                    </div>
                    <div class="form_item">
                        <label class="form_label">И-мэйл хаяг</label>
                        <input type="email" name="email" id="edit_email" class="form_input">
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal">Болих</button>
                        <button type="submit" class="__btn btn_primary ml2">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- {{ $item->links() }} --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#content'), {
        ckfinder: {
            uploadUrl: "{{ route('admin.department.upload') }}?_token={{ csrf_token() }}"
        },
        image: {
            resizeOptions: [
                { name: 'resizeImage:original', label: 'Original', value: null },
                { name: 'resizeImage:50', label: '50%', value: '50' },
                { name: 'resizeImage:75', label: '75%', value: '75' }
            ],
            toolbar: [
                'imageTextAlternative',
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side',
                'resizeImage'
            ]
        }
    })
    .catch(error => console.error(error));
</script>

<script>
    let editEditor;
    
    ClassicEditor.create(document.querySelector('#edit_content'))
        .then(editor => editEditor = editor);
    
    document.querySelectorAll('.department_edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
    
            fetch(`/admin/department/${id}/json`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_name').value = data.name;
                editEditor.setData(data.description ?? '');

                // 🔥 хуучин зураг preview
                const preview = document.getElementById('editImagePreview');
                preview.src = data.cover_image ? '/' + data.cover_image : '/images/icon_upload_file.png';

                // 🔥 menu select тохируулах
                const menuSelect = document.querySelector('#editDepartmentForm select[name="menu_id"]');
                Array.from(menuSelect.options).forEach(option => {
                    option.selected = (parseInt(option.value) === data.menu_id);
                });

                // form action
                const form = document.getElementById('editDepartmentForm');
                form.action = `/admin/department/${data.id}`;

                new bootstrap.Modal(
                    document.getElementById('editDepartmentModal')
                ).show();
            });
        });
    });

    // let editEmpModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));

    document.querySelectorAll('.edit-employee-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            fetch(`/admin/employee/${id}/json`)
                // .then(res => {
                //     console.log('status:', res.status);
                //     return res.text(); // 🔍 эхлээд raw text харъя
                // })
                // .then(text => {
                //     console.log('raw response:', text);
                //     const data = JSON.parse(text); // энд унах уу?
                //     console.log('parsed:', data);

                //     console.log('data.id =', data.id); // шалгалт

                //     document.getElementById('editEmployeeForm').action =
                //         `/admin/employee/${data.id}`;
                // })
                // .catch(err => console.error(err));
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json(); // ⭐ ЭНД JSON болгоно
                })
                .then(data => {
                    // одоо data = жинхэнэ object ✅
                    document.getElementById('edit_emp_id').value = data.id;
                    document.getElementById('edit_fullname').value = data.fullname ?? '';
                    document.getElementById('edit_position').value = data.position ?? '';
                    document.getElementById('edit_phone').value = data.phone ?? '';
                    document.getElementById('edit_email').value = data.email ?? '';
                    document.getElementById('edit_department_id').value = data.department_id;

                    document.getElementById('editEmpImagePreview').src =
                        data.photo ? '/' + data.photo : '/images/icon_upload_file.png';

                    const form = document.getElementById('editEmployeeForm');
                    form.action = `/admin/employee/${data.id}`;

                    new bootstrap.Modal(
                        document.getElementById('editEmployeeModal')
                    ).show();
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                });
        });
    });

    document.getElementById('editEmployeeForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST', // POST + @method('PUT') → Laravel handle PUT
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log('Saved:', data);
                bootstrap.Modal.getInstance(
                    document.getElementById('editEmployeeModal')
                ).hide();

                // ✅ хүсвэл table update
            } else {
                alert(data.message || 'Алдаа гарлаа');
            }
        })
        .catch(err => console.error(err));
    });

    // document.querySelectorAll('.edit-employee-btn').forEach(btn => {
    //     btn.addEventListener('click', function () {
    //         const id = this.dataset.id;
    //         debugger;
    //         fetch(`/admin/employee/${id}/json`, {
    //             method: 'GET',
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    //             }
    //         })
    //             .then(data => {
    //                 document.getElementById('edit_emp_id').value = data.id;
    //                 document.getElementById('edit_fullname').value = data.fullname;
    //                 document.getElementById('edit_position').value = data.position;
    //                 document.getElementById('edit_phone').value = data.phone;
    //                 document.getElementById('edit_email').value = data.email;
    //                 document.getElementById('edit_department_id').value = data.department_id;

    //                 document.getElementById('editEmpImagePreview').src = data.photo ? '/' + data.photo : '/images/icon_upload_file.png';

    //                 const form = document.getElementById('editEmployeeForm');
    //                 form.action = `/admin/employee/${data.id}`;

    //                 new bootstrap.Modal(
    //                     document.getElementById('editEmployeeModal')
    //                 ).show();
    //             });
    //     });
    // });
</script>

@endsection