@extends('admin.layout')
@section('content')
<div class="admin_root folder_header">
    <div class="dcsb">
        <div class="n_breadcrumb dfc">
            <ul>
                <li><a href=""><span class="bread_home"></span>Эхлэл</a></li>
                <li><p>Мэдээ мэдээллийн жагсаалт</p></li>
            </ul>
        </div>
        <div class="fline_header dfc">
            <div class="file_options dfc">
                <button class="f_f_button f_edit" id="btnRename" disabled><span></span>Солих</button>
                <button class="f_f_button f_delete" id="btnDelete" disabled><span></span>Устгах</button>
            </div>
            <div class="folder_file dfc">
                <button class="f_f_button f_file" data-bs-toggle="modal" data-bs-target="#addNews"><span></span>Мэдээ нэмэх</button>
            </div>
        </div>
    </div>
</div>
<div class="admin_file_wrap">
    <div class="file_content">
        <h2>Мэдээ мэдээлэл</h2>
        <div class="table_wrap">
            <table class="table_content">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Зураг</th>
                        <th>Гарчиг</th>
                        <th>Огноо</th>
                        <th style="width: 125px">Онцолсон эсэх</th>
                        <th>Төлөв</th>
                        <th>Хянасан алба</th>
                        <th>Хянасан ажилтан</th>
                        <th>Нийтэлсэн</th>
                        <th>Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($newsList as $key => $item)
                        <tr>
                            <td style="width: 40px">{{ $newsList->firstItem() + $key }}</td>
                            <td><div class="img_block"><img src="/{{ $item->highlight_image }}" alt=""></div></td>
                            <td>{{ $item->title }}</td>
                            {{-- <td>{{ strip_tags(Str::limit($item->content, 240)) }}</td> --}}
                            <td>{{ $item->publish_at }}</td>
                            <td>
                                @if($item->highlight)
                                    <span class="status status_success">Тийм</span>
                                @else
                                    <span class="status status_danger">Үгүй</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->status === 'draft')
                                    <span class="status status_danger">Түр хадгалсан</span>
                                @elseif ($item->status === 'pending')
                                    <span class="status status_warning">Хүлээгдэж байна</span>
                                @elseif ($item->status === 'published')
                                    <span class="status status_success">Нийтлэгдсэн</span>
                                @endif
                            </td>
                            <td>{{ $item->department->name ?? '-' }}</td>
                            <td>{{ $item->editor->name ?? '-' }}</td>
                            <td>{{ $item->publisher->name ?? '-' }}</td>
                            {{-- <td>
                                @if(auth()->user()->role === 'publisher' && $item->status === 'pending')
                                    <form action="{{ route('admin.news.publish', $item->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Нийтлэх</button>
                                    </form>
                                @endif
                            </td> --}}
                            {{-- <td>
                                @if($item->status === 'published')
                                    <span class="status status_success">Нийтлэгдсэн</span>
                                @elseif($item->publish_at > now())
                                    <span class="status status_waiting">Хүлээгдэж байна</span>
                                @endif
                            </td> --}}
                            <td>
                                <div class="dfc">
                                    <a href="{{ route('admin.news.edit', $item->id) }}" class="f_f_button f_edit"><span></span>Засах</a>
                                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="f_f_button f_delete ml1" onclick="return confirm('Та устгахдаа итгэлтэй байна уу?')"><span></span>Устгах</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">Мэдээ олдсонгүй</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addNews" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog other_modal" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Мэдээ нэмэх</h2>
            </div>
            <form id="newsForm">
                @csrf
                <div class="modal_main">
                    <div class="dg g3 gap1">
                        <div class="form_item col2to1">
                            <label class="form_label">Гарчиг</label>
                            <input name="title" class="form_input" placeholder="Гарчиг">
                        </div>
                        <div class="form_item">
                            <label class="form_label">Хамаарах цэс</label>
                            <select name="menu_id" class="form_select">
                                <option value="">— Menu-д холбох —</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                    @foreach($menu->children as $child)
                                        <option value="{{ $child->id }}">— {{ $child->title }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="dg g3 gap1">   
                        <div class="col2to1">
                            <div class="form_item">
                                <label class="form_label">Мэдээний линк /Гарчиг товч утга/</label>
                                <input name="excerpt" class="form_input" placeholder="Мэдээний линк">
                            </div>
                        </div>
                        <div class="">
                            <label class="form_label">Нийтлэх огноо</label>
                            <input type="datetime-local" name="publish_at" class="form_input">
                            <div class="__text_desc">
                                <small class="__error">Хоосон орхивол одоогийн цаг орно</small>
                            </div>
                        </div>   
                    </div>
                    <div class="form_item">
                        <label class="form_label">Мэдээний агуулга</label>
                        <textarea name="content" id="content"></textarea>
                    </div>
                    <div class="form_item">
                        <label class="form_label">Онцлох зураг</label>
                        <input type="file" name="highlight_image" class="form_input">
                    </div>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'publisher')
                    <div class="form_item">
                        <div class="dfc">
                            <label class="form-checkbox-label">
                                <input type="checkbox" name="is_active" value="0" class="form-checkbox hidden" {{ auth()->user()->role === 'editor' ? 'disabled' : '' }}>
                                <span class="form-checkmark"></span>
                                <span class="label-text">Нийтлэх</span>
                            </label>
                            @if(auth()->user()->role === 'admin')
                            <label class="form-checkbox-label ml2">
                                <input type="checkbox" name="highlight" value="0" class="form-checkbox hidden" {{ auth()->user()->role === 'editor' || auth()->user()->role === 'publisher' ? 'disabled' : '' }}>
                                <span class="form-checkmark"></span>
                                <span class="label-text">Онцлох эсэх</span>
                            </label>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal"
                        aria-label="Close">Болих</button>
                        {{-- <button type="submit" onclick="closeModal('newsModal')" class="__btn btn_primary ml2" data-bs-dismiss="modal"
                        aria-label="Close">Хадгалах</button> --}}
                        <button type="submit" class="__btn btn_primary ml2">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- <div class="ad_main_head dcsb">
    <h3>Мэдээ мэдээллийн жагсаалт</h3>
    <a href="{{ route('admin.news.create') }}" class="__btn green_btn">Шинэ мэдээ нэмэх</a>
</div> --}}

{{-- {{ $item->links() }} --}}

<script src="/js/tinymce/tinymce.min.js"></script>

<script>
tinymce.init({
    selector: '#content',
    height: 500,
    license_key: 'gpl',
    plugins: 'image table lists link code',
    toolbar: `
        undo redo | bold italic underline |
        alignleft aligncenter alignright |
        bullist numlist |
        table image link |
        code
    `,
    menubar: true,
    //   images_upload_url: '/admin/upload/news',
    images_upload_url: "{{ route('admin.news.upload') }}",
    automatic_uploads: true,
    file_picker_types: 'image',
    image_title: true,

    images_upload_handler: function (blobInfo, progress) {
        return new Promise((resolve, reject) => {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('admin.news.upload') }}");

        // ✅ ЭНЭ Л ЧУХАЛ
        xhr.setRequestHeader(
            'X-CSRF-TOKEN',
            document.querySelector('meta[name="csrf-token"]').content
        );

        xhr.onload = function () {
            if (xhr.status !== 200) {
            reject('HTTP Error: ' + xhr.status);
            return;
            }

            let json = JSON.parse(xhr.responseText);

            if (!json.location) {
            reject('Invalid response');
            return;
            }

            resolve(json.location);
        };

        let formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        xhr.send(formData);
        });
    }
});
</script>
@endsection
