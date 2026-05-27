@extends('admin.layout')

@section('content')
<div class="admin_root folder_header">
    <div class="dcsb">
        <div class="n_breadcrumb dfc">
            <ul>
                <li><a href=""><span class="bread_home"></span>Эхлэл</a></li>
                <li><p>Файлын тохиргоо</p></li>
            </ul>
            <div id="active-folder-breadcrumb" class="selected_folder"><p></p></div>
        </div>
        <div class="fline_header dfc">
            <div class="admin_search dfc" role="search">
                <input type="text" id="fileSearchInput" class="form_input" placeholder="Файлын нэр, дугаараар хайх...">
                <button type="button" id="fileSearchClear" class="__btn ml1" style="display:none;">Цэвэрлэх</button>
            </div>
            <div class="file_options dfc">
                <button class="f_f_button f_edit" id="btnEdit" disabled><span></span>Засах</button>
                <button class="f_f_button f_delete" id="btnDelete" disabled><span></span>Устгах</button>
            </div>
            <div class="folder_file dfc">
                <button class="f_f_button f_folder" data-bs-toggle="modal" data-bs-target="#addFolder"><span></span>Хавтас нэмэх</button>
                <button class="f_f_button f_file" data-bs-toggle="modal" data-bs-target="#addFile"><span></span>Файл нэмэх</button>
            </div>
        </div>
    </div>
</div>
<div class="admin_file_wrap file_manager">
    <div class="admin_page_grid dg">
        <div class="admin_page_sideber">
            <div class="admin_page_sidebar_cont">
                <ul>
                @foreach($folders as $folder)
                    <li class="{{ $folder->children->count() > 0 ? 'sub_folder' : '' }}">
                        <p class="folder_item" 
                        onclick="folderView(this, {{ $folder->id }})"
                        data-folder-name="{{ $folder->name }}"
                        ondragover="allowDrop(event)"
                        ondrop="dropFile(event, {{ $folder->id }})"
                        ><span></span><i>{{ $folder->name }}</i><button class="btn btn-danger btn-sm delete-folder" onclick="deleteFolder({{ $folder->id }})">
                            Устгах
                        </button></p>
                        @if($folder->children->count())
                            <ul>
                                @foreach($folder->children as $child)
                                    <li><p class="folder_item"
                                        onclick="folderView(this, {{ $child->id }})"
                                        data-folder-name="{{ $child->name }}"
                                        ondragover="allowDrop(event)"
                                        ondrop="dropFile(event, {{ $child->id }})"><span></span>{{ $child->name }}</p><button class="btn btn-danger btn-sm delete-folder" onclick="deleteFolder({{ $child->id }})">
                                        Устгах
                                    </button></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
        <div class="admin_page_content dg">
            <div class="file_list">
                <p class="not_file">Хавтас сонгоно уу эсвэл хайна уу</p>
            </div>
            <div class="file_preview" id="filePreview">
                <p class="empty">Файл сонгоно уу</p>
            </div>
        </div>
    </div>
</div>

<div id="moveLoader" style="
    display:none;
    position:fixed;
    top:20px;
    right:20px;
    background:#000;
    color:#fff;
    padding:10px 15px;
    border-radius:5px;
    z-index:9999;">
    Зөөж байна...
</div>

<div id="undoToast" class="file_undo">
    Файл зөөгдлөө
    <button onclick="undoMove()">Буцаах</button>
</div>

<div class="modal fade" id="addFolder" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Хавтас нэмэх</h2>
            </div>
            <form id="folderForm">
                @csrf
                <div class="modal_main">
                    <div class="form_item">
                        <label class="form_label">Хавтасны нэр</label>
                        <input type="text" class="form_input" name="name" required>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Хамаарах хавтас</label>
                        <select name="parent_id" class="form_select">
                            <option value="">— Хамаарах хавтас —</option>
                            @foreach($folders as $folder)
                                <option value="{{ $folder->id }}">
                                    {{ $folder->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal"
                        aria-label="Close">Болих</button>
                        <button type="submit" onclick="closeModal('folderModal')" class="__btn btn_primary ml2" data-bs-dismiss="modal"
                        aria-label="Close">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addFile" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Файл нэмэх</h2>
            </div>
            <form id="fileForm">
                @csrf
                <div class="modal_main">
                    <div class="form_item">
                        <label class="form_label">Файлын нэр</label>
                        <input type="text" name="title" class="form_input" placeholder="Файлын нэр" required>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Баталсан огноо</label>
                        <input type="text" name="date" class="form_input" placeholder="Баталсан огноо">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Дугаар</label>
                        <input type="text" name="number" class="form_input" placeholder="Дугаар">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Хамаарах хавтас</label>
                        <select name="folder_id" class="form_select">
                            <option value="">— Folder сонгох —</option>
                            @foreach($folders as $folder)
                                <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @foreach ($folder->children as $child)
                                    <option value="{{ $child->id }}">- {{ $child->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
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
                        <input type="file" class="form_input" name="file" required>
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal"
                        aria-label="Close">Болих</button>
                        <button type="submit" onclick="closeModal('')" class="__btn btn_primary ml2" data-bs-dismiss="modal"
                        aria-label="Close">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editFile" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Файл засах</h2>
            </div>
            <form id="editFileForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_file_id">
                <div class="modal_main">
                    <div class="form_item">
                        <label class="form_label">Файлын нэр</label>
                        <input type="text" name="title" id="edit_file_title" class="form_input" placeholder="Файлын нэр" required>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Баталсан огноо</label>
                        <input type="text" name="date" id="edit_file_date" class="form_input" placeholder="Баталсан огноо">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Дугаар</label>
                        <input type="text" name="number" id="edit_file_number" class="form_input" placeholder="Дугаар">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Хамаарах хавтас</label>
                        <select name="folder_id" id="edit_file_folder" class="form_select">
                            <option value="">— Folder сонгох —</option>
                            @foreach($folders as $folder)
                                <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @foreach ($folder->children as $child)
                                    <option value="{{ $child->id }}">- {{ $child->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Хамаарах цэс</label>
                        <select name="menu_id" id="edit_file_menu" class="form_select">
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
                        <label class="form_label">Файл солих (заавал биш)</label>
                        <input type="file" class="form_input" name="file">
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal" aria-label="Close">Болих</button>
                        <button type="submit" class="__btn btn_primary ml2">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
(function () {
    const input = document.getElementById('fileSearchInput');
    const clearBtn = document.getElementById('fileSearchClear');
    if (!input) return;

    const fileList = document.querySelector('.file_list');
    const filePreview = document.getElementById('filePreview');
    const breadcrumbItem = document.getElementById('active-folder-breadcrumb');
    let timer = null;
    let lastQuery = '';

    function renderResults(files, q) {
        if (!files.length) {
            fileList.innerHTML = `<p class="not_file">"${q}" гэсэн утгаар файл олдсонгүй</p>`;
            return;
        }
        let html = `<table class="table_content">
            <thead><tr>
                <th>Нэр</th>
                <th>Хавтас</th>
                <th>Төрөл</th>
                <th>Хэмжээ</th>
            </tr></thead><tbody>`;
        files.forEach(file => {
            const folderName = (file.folder && file.folder.name) ? file.folder.name : '-';
            html += `<tr class="file_row"
                onclick="previewFile(${file.id})"
                data-file='${JSON.stringify(file).replace(/'/g, "&#39;")}'
                draggable="true"
                ondragstart="dragFile(event, ${file.id})">
                <td><span class="file_type"></span>${file.title}</td>
                <td>${folderName}</td>
                <td>${file.mime_type ?? '-'}</td>
                <td>${file.size ? (file.size / 1024).toFixed(2)+' KB' : '-'}</td>
            </tr>`;
        });
        html += '</tbody></table>';
        fileList.innerHTML = html;
    }

    function runSearch(q) {
        if (q === lastQuery) return;
        lastQuery = q;

        if (q === '') {
            clearBtn.style.display = 'none';
            fileList.innerHTML = '<p class="not_file">Хавтас сонгоно уу эсвэл хайна уу</p>';
            filePreview.innerHTML = '<p class="empty">Файл сонгоно уу</p>';
            document.querySelectorAll('.folder_item.active').forEach(e => e.classList.remove('active'));
            if (breadcrumbItem) {
                breadcrumbItem.style.display = 'none';
                const p = breadcrumbItem.querySelector('p');
                if (p) p.textContent = '';
            }
            return;
        }

        clearBtn.style.display = '';
        fileList.innerHTML = '<p class="not_file">Хайж байна...</p>';
        if (breadcrumbItem) {
            breadcrumbItem.style.display = 'list-item';
            const p = breadcrumbItem.querySelector('p');
            if (p) p.textContent = '🔍 "' + q + '" хайлт';
        }
        document.querySelectorAll('.folder_item.active').forEach(e => e.classList.remove('active'));
        localStorage.removeItem('activeFolder');

        fetch('/admin/folders-search?q=' + encodeURIComponent(q), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(handleAjaxResponse)
        .then(data => {
            if (input.value.trim() !== q) return; // user-н шинэ хайлт давсан
            renderResults(data.files || [], q);
        })
        .catch(err => {
            console.error('File search error:', err);
            fileList.innerHTML = '<p class="not_file">Хайлтын алдаа: ' + (err.message || 'Алдаа') + '</p>';
        });
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => runSearch(this.value.trim()), 250);
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(timer);
            runSearch(this.value.trim());
        }
    });

    clearBtn.addEventListener('click', function () {
        input.value = '';
        runSearch('');
        input.focus();
    });
})();
</script>
@endsection
