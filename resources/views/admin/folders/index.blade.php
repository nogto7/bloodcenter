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
            <div class="file_options dfc">
                {{-- <button class="f_f_button f_edit" id="btnRename" disabled><span></span>Солих</button> --}}
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
                <p class="not_file">Энэ хавтсанд файл алга</p>
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
                        <input type="text" name="date" class="form_input" placeholder="Баталсан огноо" required>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Дугаар</label>
                        <input type="text" name="number" class="form_input" placeholder="Дугаар" required>
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
@endsection
