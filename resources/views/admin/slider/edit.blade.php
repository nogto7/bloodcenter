@extends('admin/layout')
@section('content')
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <h1 class="title">Слайдер засах</h1>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('admin.slider.update', $slider) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form_item">
                </div>
                <div class="form_item">
                    <label class="form_label">Гарчиг</label>
                    <input type="text" name="title" class="form_input" value="{{ old('title', $slider->title) }}">
                    @error('title')<p>{{ $message }}</p>@enderror
                </div>
                <div class="form_item">
                    <label class="form_label">Дэс дараалал</label>
                    <input type="number" name="sort" value="{{ old('sort', $slider->sort) }}" class="form_input">
                </div>  
                <div class="form_item">
                    <label class="form_label">Дэлгэрэнгүй орох мэдээний URL</label>
                    <input name="url" value="{{ old('url', $slider->url) }}" class="form_input" placeholder="Дэлгэрэнгүй орох мэдээний URL">
                </div>
                <div class="form_item">
                    <label class="form_label">Мэдээний товч агуулга</label>
                    <textarea name="desc" id="" class="form_textarea">{{ old('desc', $slider->desc) }}</textarea>
                </div>
                <div class="form_item">
                    <label>Зураг</label>
                    <input type="file" name="highlight_image">
                    @if($slider->highlight_image)
                    <div class="thumbnail_img">
                        <div class="img_block">
                            <img src="{{ asset($slider->highlight_image) }}" alt="" width="100">
                        </div>
                    </div>
                    @endif
                    @error('images')<p>{{ $message }}</p>@enderror
                </div>
                <div class="form_item">
                    <label class="form-checkbox-label">
                        <input type="checkbox" name="is_active" value="1" {{ old('active', $slider->active) ? 'checked' : '' }} class="form-checkbox hidden">
                        <span class="form-checkmark"></span>
                        <span class="label-text">Идэвхтэй эсэх</span>
                    </label>
                </div>
                <button type="submit" class="__btn btn_primary">Шинэчлэх</button>
            </form>
        </div>
    </div>
</div>
@endsection
