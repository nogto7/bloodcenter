@extends('admin.layout')
@section('content')
<!-- resources/views/admin/news/create.blade.php -->
<div class="section_container">
    <div class="section_line">
        <div class="admin_form">
            <h1 class="title">Мэдээлэл оруулах</h1>
            <form method="POST" action="{{ route('admin.menus.store') }}">
                @csrf
                <div class="dg g2 gap1">
                    <div class="dg g3 gap1">
                        <div class="form_item col2to1">
                            <label for="menu_id" class="form_label">Parent menu сонгох</label>
                            {{-- <select name="parent_id" class="form_select">
                                <option value="">— Root menu —</option>
                                @foreach($parents as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                @endforeach
                            </select> --}}
                            @php
                            function menuOptions($menus, $currentId = null, $selectedId = null, $prefix = '')
                            {
                                foreach ($menus as $menu) {
                                    if ($menu->id === $currentId) continue;

                                    $selected = $menu->id == $selectedId ? 'selected' : '';
                                    echo "<option value='{$menu->id}' {$selected}>{$prefix}{$menu->title}</option>";

                                    if ($menu->children->count()) {
                                        menuOptions($menu->children, $currentId, $selectedId, $prefix . '— ');
                                    }
                                }
                            }
                            @endphp

                            <select name="parent_id" class="form_select">
                                <option value="">-- Эх menu --</option>
                                @php menuOptions($menus); @endphp
                            </select>
                        </div>
                        <div class="form_item">
                            <label class="form_label">Цэсний төрөл</label>
                            <select name="type" class="form_select">
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('type', $menu->type ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Гарчиг</label>
                        <input name="title" class="form_input" placeholder="Гарчиг">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Цэсний линк /Route/</label>
                        <input name="url" class="form_input" placeholder="Цэсний линк">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Цэсний дараалал /order/</label>
                        <input type="number" class="form_input" name="sort" value="0"></input>
                    </div>
                    <div class="form_item">
                        <label class="form-radiobox-label">
                            <input type="radio" name="active" value="1" class="form-radiobox hidden" >
                            <span class="form-radiomark"></span>
                            <span class="label-text">Идэвхтэй эсэх</span>
                        </label>
                    </div>
                </div>
                <button class="__btn btn_primary">Хадгалах</button>
            </form>
        </div>
    </div>
</div>
@endsection
