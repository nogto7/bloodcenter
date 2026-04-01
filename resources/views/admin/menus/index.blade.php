@extends('admin.layout')

@section('content')
<div class="admin_root folder_header">
    <div class="dcsb">
        <div class="n_breadcrumb dfc">
            <ul>
                <li><a href=""><span class="bread_home"></span>Эхлэл</a></li>
                <li><p>Цэсний жагсаалт</p></li>
            </ul>
        </div>
        <div class="fline_header dfc">
            <div class="folder_file dfc">
                <button class="f_f_button f_file" data-bs-toggle="modal" data-bs-target="#addMenus"><span></span>Цэс нэмэх</button>
            </div>
        </div>
    </div>
</div>
<div class="admin_file_wrap">
    <div class="file_content">
    {{-- <div class="ad_main_head dcsb">
        <h3>Цэсний жагсаалт</h3>
        <a href="{{ route('admin.menus.create') }}" class="__btn green_btn">Цэс нэмэх</a>
    </div> --}}
        <h2>Цэс</h2>
        <div class="table_wrap">
            <table class="table_content">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Нэр</th>
                        <th>Цэсний линк /Route/</th>
                        <th>Төрөл</th>
                        <th>Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($menus as $index => $menu)
                    <tr>
                        <td style="width: 40px">{{ $index + 1 }}</td>
                        <td>{{ $menu->title }}</td>
                        <td>{{ $menu->url }}</td>
                        <td>{{ $menu->type }}</td>
                            <td style="width: 170px">
                            <div class="dfc">
                                <a href="{{ route('admin.menus.edit', $menu) }}" class="f_f_button f_edit"><span></span>Засах</a>
                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="f_f_button f_delete ml1" onclick="return confirm('Та устгахдаа итгэлтэй байна уу?')"><span></span>Устгах</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @if($menu->children->isNotEmpty())
                        <tr>
                            <td colspan="5">
                                <table class="table_content">
                                    @foreach($menu->children as $index => $child)
                                    <tr>
                                        <td style="width: 40px">{{ $index + 1 }}</td>
                                        <td>{{ $child->title }}</td>
                                        <td>{{ $child->url }}</td>
                                        <td>{{ $child->type }}</td>
                                        <td style="width: 170px">
                                            <div class="dfc">
                                                <a href="{{ route('admin.menus.edit', $child) }}" class="f_f_button f_edit"><span></span>Засах</a>
                                                <form action="{{ route('admin.menus.destroy', $child) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="f_f_button f_delete ml1" onclick="return confirm('Та устгахдаа итгэлтэй байна уу?')"><span></span>Устгах</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="addMenus" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Цэс нэмэх</h2>
            </div>
            <form id="menusForm">
                @csrf
                <div class="modal_main">
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
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal"
                        aria-label="Close">Болих</button>
                        <button type="submit" onclick="closeModal('menusModal')" class="__btn btn_primary ml2" data-bs-dismiss="modal"
                        aria-label="Close">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
