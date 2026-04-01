@extends('admin.layout')
@section('content')
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <h1 class="title">Видео оруулах</h1>
            <form method="POST" action="{{ route('admin.video.store') }}">
                @csrf
                <div class="dg g2 gap2">
                    <div class="form_item">
                        <label class="form_label">Гарчиг</label>
                        <input name="title" class="form_input" placeholder="Гарчиг">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Youtube-n URL id</label>
                        <input name="url" class="form_input" placeholder="Мэдээний линк">
                    </div>
                </div>
                <div class="form_item col2to1">
                    <div class="dfc">
                        <label class="form-radiobox-label">
                            <input type="radio" name="is_active" value="1" class="form-radiobox hidden" checked>
                            <span class="form-radiomark"></span>
                            <span class="label-text">Нийтлэх</span>
                        </label>
                    </div>
                </div>
                <button class="__btn btn_primary">Хадгалах</button>
            </form>
        </div>
    </div>
</div>
@endsection
