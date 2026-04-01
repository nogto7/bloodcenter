@extends('admin.layout')
@section('content')
<!-- resources/views/admin/news/create.blade.php -->
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <h1 class="title">Слайдер оруулах</h1>
            <form method="POST" action="{{ route('admin.slider.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="dg g2 gap2">
                    <div class="form_item">
                        <label class="form_label">Гарчиг</label>
                        <input name="title" class="form_input" placeholder="Гарчиг">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Дэс дараалал</label>
                        <input type="number" name="sort" class="form_input" placeholder="Дэс дараалал">
                    </div>
                </div>
                <div class="form_item">
                    <label class="form_label">Дэлгэрэнгүй орох мэдээний URL</label>
                    <input name="url" class="form_input" placeholder="Дэлгэрэнгүй орох мэдээний URL">
                </div>
                <div class="form_item">
                    <label class="form_label">Мэдээний товч агуулга</label>
                    <textarea name="desc" id="" class="form_textarea" placeholder="Мэдээний товч агуулга"></textarea>
                </div>
                <div class="form_item">
                    <label class="form_label">Зураг</label>
                    <input type="file" name="highlight_image" class="form_input">
                </div>
                <div class="form_item">
                    <div class="dfc">
                        <label class="form-radiobox-label">
                            <input type="radio" name="active" value="1" class="form-radiobox hidden" checked>
                            <span class="form-radiomark"></span>
                            <span class="label-text">Нийтлэх</span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="__btn btn_primary">Хадгалах</button>
            </form>
        </div>
    </div>
</div>
@endsection
