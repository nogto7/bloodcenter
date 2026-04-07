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
                            <input name="title" class="form_input" placeholder="Гарчиг">
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
                        <button type="button" class="__btn" data-bs-dismiss="modal"
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
                            <input name="title" id="edit_title" class="form_input" placeholder="Гарчиг">
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
                        <button type="button" class="__btn" data-bs-dismiss="modal"
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
                        <input type="text" name="title" class="form_input" placeholder="Гарчиг">
                    </div>
                    <div class="form_item" id="text-box">
                        <label class="form_label">Мэдээний агуулга</label>
                        <textarea name="content" id="content"></textarea>
                    </div>
                    <div class="form_item" id="link-box" style="display: none;">
                        <label class="form_label">Мэдээний холбоос</label>
                        <input type="text" name="link" class="form_input" placeholder="Мэдээний холбоос оруулна уу">
                    </div>
                    <div class="form_item" id="file-box" style="display: none;">
                        <label class="form_label">Сонгосон файл</label>
                        <input type="hidden" name="file_id" id="file_id" value="{{ old('file_id', $item->file_id ?? '') }}">
                        <span class="text_notice mb1" id="selected_file">Сонгоогүй</span>
                        <input type="text" id="fileSearch" placeholder="Файл хайх..." class="form_input">
                        <div class="file-picker-grid dg g3 gap1" id="filePicker"></div>
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
                    <div class="form_item" id="text-box">
                        <label class="form_label">Мэдээний агуулга</label>
                        <textarea name="content" id="content"></textarea>
                    </div>
                    <div class="form_item" id="link-box" style="display: none;">
                        <label class="form_label">Мэдээний холбоос</label>
                        <input type="text" name="link" id="edit_link" class="form_input" placeholder="Мэдээний холбоос оруулна уу">
                    </div>
                    <div class="form_item" id="file-box" style="display: none;">
                        <label class="form_label">Сонгосон файл</label>
                        <input type="hidden" name="file_id" id="file_id" value="{{ old('file_id', $item->file_id ?? '') }}">
                        <span class="text_notice mb1" id="selected_file">Сонгоогүй</span>
                        <input type="text" id="fileSearch" placeholder="Файл хайх..." class="form_input">
                        <div class="file-picker-grid dg g3 gap1" id="filePicker"></div>
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

<script src="/js/tinymce/tinymce.min.js"></script>

<script>
    function loadFiles(search = '') {
        fetch('/admin/files')
            .then(res => res.json())
            .then(files => {
                const grid = document.getElementById('filePicker');
                grid.innerHTML = '';

                files
                    .filter(f => f.title.toLowerCase().includes(search.toLowerCase()))
                    .forEach(f => {
                        const div = document.createElement('div');
                        div.classList.add('file-item');
                        div.innerHTML = f.mime_type.startsWith('image')
                            ? `<img src="/storage/${f.path}" style="max-width:100px;"><p>${f.title}</p>`
                            : `<p>📄 ${f.title}</p>`;
                        
                        div.onclick = () => selectFile(f);
                        grid.appendChild(div);
                    });
            });
    }

    document.getElementById('fileSearch').addEventListener('input', e => {
        loadFiles(e.target.value);
    });

    $('#addItem').on('shown.bs.modal', function () {
        loadFiles();
    });

    function selectFile(file) {
        const fileInput = document.getElementById('file_id');
        const selected = document.getElementById('selected_file');

        if (!fileInput || !selected) {
            console.warn('File input or display div not found');
            return;
        }

        fileInput.value = file.id;

        if (file.mime_type?.startsWith('image')) {
            selected.innerHTML = `<img src="/storage/${file.path}" style="max-width:100px;"><span>${file.title}</span>`;
        } else {
            selected.innerHTML = `<span>📄 ${file.title}</span>`;
        }

        // File manager modal хаах, хэрэв хэрэгтэй бол
        // $('#fileManagerModal').modal('hide');
    }

    document.getElementById('type').addEventListener('change', function () {
    let type = this.value;

    document.getElementById('text-box').style.display =
        (type === 'text') ? 'block' : 'none';

    document.getElementById('link-box').style.display =
        (type === 'link') ? 'block' : 'none';

    document.getElementById('file-box').style.display =
        (type === 'image' || type === 'file') ? 'block' : 'none';
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

    document.querySelectorAll('.edit-items-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            fetch(`/admin/group-items/${id}/json`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json(); // ⭐ ЭНД JSON болгоно
                })
                .then(data => {

                    const form = document.getElementById('itemEditForm');

                    form.querySelector('#edit_group_id').value = data.group_id;
                    form.querySelector('#edit_type').value = data.type;
                    form.querySelector('[name="title"]').value = data.title;
                    form.querySelector('[name="link"]').value = data.link ?? '';
                    tinymce.get('content').setContent(data.content ?? '');

                    document.getElementById('itemEditForm').dataset.id = id;

                    document.getElementById('type').dispatchEvent(new Event('change'));
                    toggleFields(data.type, document.getElementById('editItem'));

                    new bootstrap.Modal(
                        document.getElementById('editItem')
                    ).show();

                    document.getElementById('edit_type').addEventListener('change', function () {
                        toggleFields(this.value, document.getElementById('editItem'));
                    });
                });
        });
    });

    function toggleFields(type, container) {
        container.querySelector('#text-box').style.display =
            (type === 'text') ? 'block' : 'none';

        container.querySelector('#link-box').style.display =
            (type === 'link') ? 'block' : 'none';

        container.querySelector('#file-box').style.display =
            (type === 'image' || type === 'file') ? 'block' : 'none';
    }

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

tinymce.init({
  selector: '#content',
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