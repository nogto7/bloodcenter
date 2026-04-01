@extends('admin/layout')
@section('content')
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <h1 class="title">Мэдээллийг засах</h1>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form_item">
                </div>
                <div class="dg g3 gap1">
                    <div class="form_item">
                        <label class="form_label">Гарчиг</label>
                        <input type="text" name="title" class="form_input" value="{{ old('title', $news->title) }}">
                        @error('title')<p>{{ $message }}</p>@enderror
                    </div>
                    <div class="form_item">
                        <label class="form_label">Хамаарах цэс</label>
                        <select name="menu_id" class="form_select">
                            <option value="">— Menu-д холбох —</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}" 
                                    {{ old('menu_id', $news->menu_id) == $menu->id ? 'selected' : '' }}>
                                    {{ $menu->title }}
                                </option>
                                @foreach($menu->children as $child)
                                    <option value="{{ $child->id }}" 
                                        {{ old('menu_id', $news->menu_id) == $child->id ? 'selected' : '' }}>
                                        — {{ $child->title }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form_item">
                    <label class="form_label">Нийтлэх огноо</label>
                    <input type="datetime-local" name="publish_at" value="{{ old('publish_at', $news->publish_at) }}" class="form_input">
                </div>  
                <div class="form_item">
                    <label class="form_label">Товч агуулга</label>
                    <textarea name="excerpt" class="form_textarea">{{ old('excerpt', $news->excerpt) }}</textarea>
                    @error('excerpt')<p>{{ $message }}</p>@enderror
                </div>
                <div class="form_item">
                    <label class="form_label">Агуулга</label>
                    <textarea name="content" id="content" class="form_textarea">{{ old('content', $news->content) }}</textarea>
                    @error('content')<p>{{ $message }}</p>@enderror
                </div>
                <div class="form_item">
                    <label>Онцлох зураг</label>
                    <input type="file" name="highlight_image">
                    @if($news->highlight_image)
                    <div class="thumbnail_img">
                        <div class="img_block"><img src="{{ asset($news->highlight_image) }}" alt="" width="100"></div>
                    </div>
                    @endif
                    @error('images')<p>{{ $message }}</p>@enderror
                </div>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'publisher')
                <div class="form_item">
                    <div class="dfc">
                        <label class="form-checkbox-label">
                            <input type="checkbox" name="is_active" value="0" {{ old('is_active', $news->is_active) ? 'checked' : '' }} class="form-checkbox hidden">
                            <span class="form-checkmark"></span>
                            <span class="label-text">Нийтлэх</span>
                        </label>
                        @if(auth()->user()->role === 'admin')
                        <label class="form-checkbox-label ml2">
                            <input type="checkbox" name="highlight" value="0" {{ old('highlight', $news->highlight) ? 'checked' : '' }} class="form-checkbox hidden">
                            <span class="form-checkmark"></span>
                            <span class="label-text">Онцлох эсэх</span>
                        </label>
                        @endif
                    </div>
                </div>
                @endif
                <button type="submit" class="__btn btn_primary">Шинэчлэх</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#content'), {
        ckfinder: {
            uploadUrl: "{{ route('admin.news.upload') }}?_token={{ csrf_token() }}"
        },
        image: {
            resizeOptions: [
                { name: 'resizeImage:original', label: '100%', value: null },
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
@endsection
