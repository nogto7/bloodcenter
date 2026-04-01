@extends('admin.layout')

@section('content')
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <div class="ad_main_head dcsb">
                <h3>Folder үүсгэх</h3>
            </div>
            <form method="POST" action="{{ route('admin.folders.store') }}">
                @csrf
                <div class="dg g2 gap2">
                    <div class="form_item">
                        <label class="form_label">Folder нэр</label>
                        <input type="text" class="form_input" name="name" required>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Parent folder</label>
                        <select name="parent_id" class="form_select">
                            <option value="">— Root folder —</option>
                            @foreach($folders as $folder)
                                <option value="{{ $folder->id }}">
                                    {{ $folder->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="__btn btn_primary">Хадгалах</button>
            </form>
        </div>
    </div>
</div>
@endsection
