@extends('admin.layout')
@section('content')
<!-- resources/views/admin/news/create.blade.php -->
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <h1 class="title">Мэдээлэл оруулах</h1>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li class="__error">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="dg g3 gap1">
                    <div class="form_item col2to1">
                        <label class="form_label">Гарчиг</label>
                        <input name="title" class="form_input" placeholder="Гарчиг" value="{{ old('title') }}">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Хамаарах цэс</label>
                        <select name="menu_id" class="form_select">
                            <option value="">— Menu-д холбох —</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}" {{ old('menu_id') == $menu->id ? 'selected' : '' }}>{{ $menu->title }}</option>
                                @foreach($menu->children as $child)
                                    <option value="{{ $child->id }}" {{ old('menu_id') == $child->id ? 'selected' : '' }}>— {{ $child->title }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form_item">
                    <label class="form_label">Нийтлэх огноо</label>
                    <input type="datetime-local" name="publish_at" class="form_input">
                    <div class="__text_desc">
                        <small class="__error">Хоосон орхивол одоогийн цаг автоматаар орно</small>
                    </div>
                </div>                
                <div class="form_item">
                    <label class="form_label">Мэдээний линк /Гарчиг товч утга/</label>
                    <input name="excerpt" class="form_input" placeholder="Мэдээний линк" value="{{ old('excerpt') }}">
                </div>
                <div class="form_item">
                    <label class="form_label">Мэдээний агуулга</label>
                    <textarea name="content" id="content">{{ old('content') }}</textarea>
                </div>
                <div class="form_item">
                    <label class="form_label">Онцлох зураг</label>
                    <input type="file" name="highlight_image" class="form_input" accept="image/*">
                    @error('highlight_image')<p class="__error">{{ $message }}</p>@enderror
                </div>
                <div class="form_item">
                    <div class="dfc">
                        <label class="form-checkbox-label">
                            <input type="checkbox" name="is_active" value="1" class="form-checkbox hidden" checked>
                            <span class="form-checkmark"></span>
                            <span class="label-text">Нийтлэх</span>
                        </label>
                        <label class="form-checkbox-label ml2">
                            <input type="checkbox" name="highlight" value="0" class="form-checkbox hidden" >
                            <span class="form-checkmark"></span>
                            <span class="label-text">Онцлох эсэх</span>
                        </label>
                    </div>
                </div>
                <button class="__btn btn_primary" type="submit">Хадгалах</button>
            </form>
        </div>
    </div>
</div>
@include('admin.partials.tinymce')
@endsection
