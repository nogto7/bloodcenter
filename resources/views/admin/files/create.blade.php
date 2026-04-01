@extends('admin.layout')

@section('content')
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <div class="ad_main_head dcsb">
                <h3>Файл оруулах</h3>
            </div>
            <form method="POST" action="{{ route('admin.files.store') }}" enctype="multipart/form-data">
            @csrf
                <div class="dg g2 gap2">
                    <div class="form_item">
                        <input type="text" name="title" class="form_input" placeholder="Файлын нэр" required>
                    </div>
                    <div class="form_item">
                        <input type="text" name="date" class="form_input" placeholder="Баталсан огноо" required>
                    </div>
                    <div class="form_item">
                        <input type="text" name="number" class="form_input" placeholder="Дугаар" required>
                    </div>
                    <div class="form_item">
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
                <button type="submit" class="__btn btn_primary">Upload</button>
            </form>
        </div>
    </div>
</div>
@endsection
