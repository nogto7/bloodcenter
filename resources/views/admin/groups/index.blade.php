@extends('admin.layout')
@section('content')
<div class="admin_root folder_header">
    <div class="dcsb">
        <div class="n_breadcrumb dfc">
            <ul>
                <li><a href=""><span class="bread_home"></span>Эхлэл</a></li>
                <li><p>Шил ажиллагаа жагсаалт</p></li>
            </ul>
        </div>
        <div class="fline_header dfc">
            <div class="file_options dfc">
                <button class="f_f_button f_edit" id="btnRename" disabled><span></span>Солих</button>
                <button class="f_f_button f_delete" id="btnDelete" disabled><span></span>Устгах</button>
            </div>
            <div class="folder_file dfc">
                <button class="f_f_button f_folder" data-bs-toggle="modal" data-bs-target="#addGroup"><span></span>Бүлэг нэмэх</button>
                <button class="f_f_button f_file ml1" data-bs-toggle="modal" data-bs-target="#addItem"><span></span>Шил ажиллааны файл</button>
            </div>
        </div>
    </div>
</div>
<div class="admin_file_wrap">
    <div class="file_content">
        <h2>Шил ажиллагаа жагсаалт</h2>
        <div class="table_wrap">
            <table class="table_content">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Гарчиг</th>
                        <th>Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($groups as $key => $item)
                        <tr>
                            <td style="width: 40px">{{ $key + 1 }}</td>
                            <td>{{ $item->title }}</td>
                            <td style="width: 192px">
                                <div class="dfc">
                                    <button type="button" class="f_f_button f_edit edit-group-btn" data-id="{{ $item->id }}"><span></span></button>
                                    <form action="{{ route('admin.groups.destroy', $item) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="f_f_button f_delete ml1" onclick="return confirm('Та устгахдаа итгэлтэй байна уу?')"><span></span>Устгах</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @if($item->items->isNotEmpty())
                        <tr>
                            <td colspan="3">
                                <ul class="shilen_file">
                                    @foreach($item->items as $i)
                                    <li>
                                        <p>{{ $i->title }}</p>
                                        @if($i->type == 'file' && $i->file)
                                            <a href="{{ asset('storage/'.$i->file->path) }}" target="_blank">{{ $i->file->title }}</a>
                                        @endif
                                        @if($i->type == 'link' && $i->link)
                                        <a href="/{{ $i->link }}" target="_blank">{{ $i->link }}</a>
                                        @endif
                                        <p>{{ $i->type }}</p>
                                        <div class="dfc">
                                            <button type="button" class="f_f_button f_edit edit-items-btn" data-id="{{ $i->id }}"><span></span>
                                            </button>
                                            <form action="{{ route('admin.group-items.destroy', $i) }}" method="POST" style="display:inline-block;">
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
                            <td colspan="7"><span>Шил ажиллагааны файл оруулаагүй</span></td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addGroup" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Бүлэг нэмэх</h2>
            </div>
            <form id="groupEditForm">
                @csrf
                <div class="modal_main">
                    <div class="">
                        <div class="form_item">
                            <label class="form_label">Гарчиг</label>
                            <input name="title" class="form_input" placeholder="Гарчиг" required>
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
                        <div class="form_item">
                            <label class="form_label">Дараалал /order/</label>
                            <input type="number" class="form_input" name="sort" value="0"></input>
                        </div>
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn"
                        aria-label="Close">Болих</button>
                        <button type="submit" class="__btn btn_primary ml2">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editGroup" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Бүлэг нэмэх</h2>
            </div>
            <form id="groupForm">
                @csrf
                <div class="modal_main">
                    <div class="">
                        <div class="form_item">
                            <label class="form_label">Гарчиг</label>
                            <input name="title" id="edit_title" class="form_input" placeholder="Гарчиг" required>
                        </div>
                        <div class="form_item">
                            <label class="form_label">Хамаарах цэс</label>
                            <select name="menu_id" id="edit_menu_id" class="form_select">
                                <option value="">— Menu-д холбох —</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                    @foreach($menu->children as $child)
                                        <option value="{{ $child->id }}">— {{ $child->title }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="form_item">
                            <label class="form_label">Дараалал /order/</label>
                            <input type="number" class="form_input" name="sort" value="0"></input>
                        </div>
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" 
                        aria-label="Close">Болих</button>
                        <button type="submit" class="__btn btn_primary ml2">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addItem" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog other_modal" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Шил ажиллаанд харуулах мэдээлэл</h2>
            </div>
            <form id="itemForm">
                @csrf
                <div class="modal_main">
                    <div class="dg g2 gap1_6">
                        <div class="form_item">
                            <label class="form_label">Бүлэг</label>
                            <select name="group_id" class="form_select">
                                @foreach($groupName as $gn)
                                    <option value="{{ $gn->id }}">
                                        {{ $gn->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form_item">
                            <label class="form_label">Төрөл</label>
                            <select name="type" class="form_select" id="type">
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('type', $menu->type ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Гарчиг</label>
                        <input type="text" name="title" class="form_input" placeholder="Гарчиг" required>
                    </div>
                    <div class="form_item" id="text-box-add">
                        <label class="form_label">Мэдээний агуулга</label>
                        <textarea name="content" id="content_add"></textarea>
                    </div>
                    <div class="form_item" id="link-box-add" style="display: none;">
                        <label class="form_label">Мэдээний холбоос</label>
                        <input type="text" name="link" class="form_input" placeholder="Мэдээний холбоос оруулна уу">
                    </div>
                    <div class="form_item" id="file-box-add" style="display: none;">
                        <label class="form_label">Сонгосон файл</label>
                        <input type="hidden" name="file_id" id="file_id_add" value="{{ old('file_id', $item->file_id ?? '') }}">
                        <span class="file_item mb1" id="selected_file_add">Сонгоогүй</span>
                        <input type="text" id="fileSearch_add" placeholder="Файл хайх..." class="form_input">
                        <div class="file-picker" id="filePicker_add"></div>
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" 
                        aria-label="Close">Болих</button>
                        <button type="submit" id="saveItemBtn" class="__btn btn_primary ml2" 
                        aria-label="Close">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editItem" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog other_modal" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Шил ажиллаанд харуулах мэдээлэл</h2>
            </div>
            <form id="itemEditForm">
                @csrf
                <div class="modal_main">
                    <div class="dg g2 gap1_6">
                        <div class="form_item">
                            <label class="form_label">Бүлэг</label>
                            <select name="group_id" id="edit_group_id" class="form_select">
                                @foreach($groupName as $gn)
                                    <option value="{{ $gn->id }}">
                                        {{ $gn->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form_item">
                            <label class="form_label">Төрөл</label>
                            <select name="type" class="form_select" id="edit_type">
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('type', $label->type ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Гарчиг</label>
                        <input type="text" name="title" id="edit_title" class="form_input" placeholder="Гарчиг">
                    </div>
                    <div class="form_item" id="text-box-edit">
                        <label class="form_label">Мэдээний агуулга</label>
                        <textarea name="content" id="content_edit"></textarea>
                    </div>
                    <div class="form_item" id="link-box-edit" style="display: none;">
                        <label class="form_label">Мэдээний холбоос</label>
                        <input type="text" name="link" id="edit_link" class="form_input" placeholder="Мэдээний холбоос оруулна уу">
                    </div>
                    <div class="form_item" id="file-box-edit" style="display: none;">
                        <label class="form_label">Сонгосон файл</label>
                        <input type="hidden" name="file_id" id="file_id_edit" value="{{ old('file_id', $item->file_id ?? '') }}">
                        <span class="file_item mb1" id="selected_file_edit">Сонгоогүй</span>
                        <input type="text" id="fileSearch_edit" placeholder="Файл хайх..." class="form_input">
                        <div class="file-picker" id="filePicker_edit"></div>
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn"
                        aria-label="Close">Болих</button>
                        <button type="submit" onclick="closeModal('departmentModal')" class="__btn btn_primary ml2" 
                        aria-label="Close">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/js/tinymce/tinymce.min.js"></script>

<script>
    function loadFiles(type = 'add', search = '') {
        fetch('/admin/files')
            .then(res => res.json())
            .then(files => {
                const grid = document.getElementById(`filePicker_${type}`);
                grid.innerHTML = '';

                files
                    .filter(f => f.title.toLowerCase().includes(search.toLowerCase()))
                    .forEach(f => {
                        const div = document.createElement('div');
                        div.classList.add('file_item');

                        div.innerHTML = f.mime_type.startsWith('image')
                            ? `<img src="/storage/${f.path}" style="max-width:100px;"><p>${f.title}</p>`
                            : `<p>📄 ${f.title}</p>`;

                        div.onclick = () => selectFile(f, div, type);

                        grid.appendChild(div);
                    });
            });
    }

    document.getElementById('fileSearch_add')
        ?.addEventListener('input', e => loadFiles('add', e.target.value));

    document.getElementById('fileSearch_edit')
        ?.addEventListener('input', e => loadFiles('edit', e.target.value));

    $('#addItem').on('shown.bs.modal', function () {
        loadFiles('add');

        const type = document.getElementById('type').value;
        toggleFields(type, 'add');
    });

    $('#editItem').on('shown.bs.modal', function () {
        loadFiles('edit');

        const type = document.getElementById('edit_type').value;
        toggleFields(type, 'edit');
    });

    function selectFile(file, el, type = 'add') {
        const fileInput = document.getElementById(`file_id_${type}`);
        const selected = document.getElementById(`selected_file_${type}`);

        document.querySelectorAll(`#filePicker_${type} .file_item`).forEach(item => {
            item.classList.remove('active');
        });

        el.classList.add('active');

        fileInput.value = file.id;

        selected.classList.add('selected');

        if (file.mime_type?.startsWith('image')) {
            selected.innerHTML = `
                <div class="selected-box">
                    <img src="/storage/${file.path}" style="max-width:100px;">
                    <span>${file.title}</span>
                </div>`;
        } else {
            selected.innerHTML = `<p>📄 ${file.title}</p><span></span>`;
        }
    }

    document.getElementById('type').addEventListener('change', function () {
        toggleFields(this.value, 'add');
    });

    document.querySelectorAll('.edit-group-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            fetch(`/admin/groups/${id}/json`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    const form = document.getElementById('groupForm');

                    form.querySelector('#edit_title').value = data.title ?? '';
                    form.querySelector('#edit_menu_id').value = data.menu_id ?? '';
                    form.querySelector('[name="sort"]').value = data.sort ?? 0;

                    new bootstrap.Modal(document.getElementById('editGroup')).show();
                });
        });
    });

    document.getElementById('groupEditForm').addEventListener('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);

        fetch('/admin/groups', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(() => location.reload());
    });

    document.getElementById('saveItemBtn').addEventListener('click', function () {
        tinymce.triggerSave();

        let form = document.getElementById('itemForm');
        let formData = new FormData(form);

        fetch('/admin/group-items', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(() => location.reload());
    });

    document.getElementById('itemEditForm').addEventListener('submit', function(e){
        e.preventDefault();

        let id = this.dataset.id;
        let formData = new FormData(this);

        let url = '/admin/group-items';
        
        if(id){
            url = `/admin/group-items/${id}`;
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(() => location.reload());
    });

    document.querySelectorAll('.edit-items-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            fetch(`/admin/group-items/${id}/json`)
                .then(res => res.json())
                .then(data => {

                    const form = document.getElementById('itemEditForm');

                    form.dataset.id = id;

                    const editType = document.getElementById('edit_type');

                    form.querySelector('#edit_group_id').value = data.group_id;
                    form.querySelector('#edit_type').value = data.type;
                    form.querySelector('[name="title"]').value = data.title ?? '';
                    form.querySelector('#edit_link').value = data.link ?? '';

                    toggleFields(data.type, 'edit');

                    tinymce.get('content_edit').setContent(data.content ?? '');

                    // 🔥 FILE SET
                    if (data.file_id && data.file) {
                        const selected = document.getElementById('selected_file_edit');
                        document.getElementById('file_id_edit').value = data.file_id;

                        if (data.file.mime_type.startsWith('image')) {
                            selected.innerHTML = `
                                <div class="selected-box">
                                    <img src="/storage/${data.file.path}" style="max-width:100px;">
                                    <span>${data.file.title}</span>
                                </div>`;
                        } else {
                            selected.innerHTML = `<p>📄 ${data.file.title}</p><span></span>`;
                        }

                        selected.classList.add('selected');
                    }

                    // toggleFields(data.type, document.getElementById('editItem'));

                    new bootstrap.Modal(document.getElementById('editItem')).show();
                });
        });
    });

    function toggleFields(type, mode = 'add') {
        document.getElementById(`text-box-${mode}`).style.display =
            (type === 'text') ? 'block' : 'none';

        document.getElementById(`link-box-${mode}`).style.display =
            (type === 'link') ? 'block' : 'none';

        document.getElementById(`file-box-${mode}`).style.display =
            (type === 'image' || type === 'file') ? 'block' : 'none';
    }

    document.getElementById('itemEditForm').addEventListener('submit', function(e){
        e.preventDefault();

        tinymce.triggerSave();

        let id = this.dataset.id;
        let formData = new FormData(this);

        let url = '/admin/group-items';
        
        if(id){
            url = `/admin/group-items/${id}`;
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(() => location.reload());
    });

    tinymce.init({
        selector: '#content_add, #content_edit',
        height: 500,
        license_key: 'gpl',
        plugins: 'image table lists link code',
        toolbar: `
            undo redo | bold italic underline |
            alignleft aligncenter alignright |
            bullist numlist |
            table image link |
            code
        `,
        menubar: true,
        //   images_upload_url: '/admin/upload/news',
        images_upload_url: "{{ route('admin.news.upload') }}",
        automatic_uploads: true,
        file_picker_types: 'image',
        image_title: true,

        images_upload_handler: function (blobInfo, progress) {
            return new Promise((resolve, reject) => {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', "{{ route('admin.news.upload') }}");

            // ✅ ЭНЭ Л ЧУХАЛ
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                document.querySelector('meta[name="csrf-token"]').content
            );

            xhr.onload = function () {
                if (xhr.status !== 200) {
                reject('HTTP Error: ' + xhr.status);
                return;
                }

                let json = JSON.parse(xhr.responseText);

                if (!json.location) {
                reject('Invalid response');
                return;
                }

                resolve(json.location);
            };

            let formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
            });
        }
    });
</script>
@endsection