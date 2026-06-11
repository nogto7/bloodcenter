@extends('admin/layout')
@section('content')
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <h1 class="title">Алба засах</h1>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li class="__error">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.department.update', $department) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="dg g2 gap1">
                    <div class="form_item">
                        <label class="form_label">Алба</label>
                        <input type="text" name="name" class="form_input" id="edit_name" value="{{ old('name', $department->name) }}" placeholder="Албаны нэр">
                        @error('name')<p class="__error">{{ $message }}</p>@enderror
                    </div>
                    <div class="dg g3 gap1">
                        <div class="col2to1">
                            <div class="form_item">
                                <label class="form_label">Хамаарах цэс</label>
                                <select name="menu_id" class="form_select">
                                    <option value="">— Menu-д холбох —</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" 
                                            {{ old('menu_id', $department->menu_id) == $menu->id ? 'selected' : '' }}>
                                            {{ $menu->title }}
                                        </option>
                                        @foreach($menu->children as $child)
                                            <option value="{{ $child->id }}" 
                                                {{ old('menu_id', $department->menu_id) == $child->id ? 'selected' : '' }}>
                                                — {{ $child->title }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form_item">
                            <label class="form_label">Өнгө сонгох</label>
                            <input type="color" name="color" value="{{ old('color', $department->color ?? '#000000') }}" />
                        </div>
                    </div>
                </div>
                <div class="form_item">
                    <label class="form_label">Ерөнхий мэдээлэл</label>
                    <textarea name="description" id="content" class="form_textarea">{{ old('description', $department->description) }}</textarea>
                </div>
                <div class="form_item">
                    <label class="form_label">Зураг</label>
                    <input type="file" name="cover_image" accept="image/*">
                    @if($department->cover_image)
                    <div class="thumbnail_img">
                        <div class="img_block"><img src="{{ asset($department->cover_image) }}" alt="" width="100"></div>
                    </div>
                    @endif
                    @error('cover_image')<p class="__error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="__btn btn_primary">Шинэчлэх</button>
            </form>
        </div>
    </div>
</div>
@include('admin.partials.tinymce', ['uploadUrl' => route('admin.department.upload')])
@endsection
