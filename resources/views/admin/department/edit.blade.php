@extends('admin/layout')
@section('content')
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <h1 class="title">Алба засах</h1>
            {{-- @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif --}}
            <form action="{{ route('admin.department.update', $department) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="dg g2 gap1">
                    <div class="form_item">
                        <label class="form_label">Алба</label>
                        <input type="text" name="name" class="form_input" id="edit_name" value="{{ old('title', $department->name) }}" placeholder="Албаны нэр">
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
                    <input type="file" name="cover_image">
                    @if($department->cover_image)
                    <div class="thumbnail_img">
                        <div class="img_block"><img src="{{ asset($department->cover_image) }}" alt="" width="100"></div>
                    </div>
                    @endif
                    @error('images')<p>{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="__btn btn_primary">Шинэчлэх</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#content'), {
        ckfinder: {
            uploadUrl: "{{ route('admin.department.upload') }}?_token={{ csrf_token() }}"
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
