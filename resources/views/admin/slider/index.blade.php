@extends('admin.layout')
@section('content')
<div class="admin_root folder_header">
    <div class="dcsb">
        <div class="n_breadcrumb dfc">
            <ul>
                <li><a href=""><span class="bread_home"></span>Эхлэл</a></li>
                <li><p>Слайдерийн жагсаалт</p></li>
            </ul>
        </div>
        <div class="fline_header dfc">
            <div class="file_options dfc">
                <button class="f_f_button f_edit" id="btnRename" disabled><span></span>Солих</button>
                <button class="f_f_button f_delete" id="btnDelete" disabled><span></span>Устгах</button>
            </div>
            <div class="folder_file dfc">
                <button class="f_f_button f_file" data-bs-toggle="modal" data-bs-target="#addSlider"><span></span>Слайдер нэмэх</button>
            </div>
        </div>
    </div>
</div>
<div class="admin_file_wrap">
    <div class="file_content">
        <h2>Слайдер</h2>
        <div class="table_wrap">
            <table class="table_content">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Зураг</th>
                        <th>Гарчиг</th>
                        <th>URL</th>
                        <th>Төлөв</th>
                        <th>Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sliderList as $key => $item)
                        <tr>
                            <td style="width: 40px">{{ $sliderList->firstItem() + $key }}</td>
                            <td style="width: 120px"><div class="img_block"><img src="/{{ $item->highlight_image }}" alt=""></div></td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->url }}</td>
                            <td>
                                @if($item->active)
                                    <span class="status status_success">Идэвхтэй</span>
                                @else
                                    <span class="status status_waiting">Идэвхгүй</span>
                                @endif
                            </td>
                            <td style="width: 160px">
                                <div class="dfc">
                                    <a href="{{ route('admin.slider.edit', $item->id) }}" class="f_f_button f_edit"><span></span>Засах</a>
                                    <form action="{{ route('admin.slider.destroy', $item) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="f_f_button f_delete ml1" onclick="return confirm('Та устгахдаа итгэлтэй байна уу?')"><span></span>Устгах</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Мэдээ олдсонгүй</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- {{ $item->links() }} --}}
        </div>
    </div>
</div>
<div class="modal fade" id="addSlider" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Слайдер нэмэх</h2>
            </div>
            <form id="sliderForm">
                @csrf
                <div class="modal_main">
                    <div class="dg g4 gap1">
                        <div class="form_item gc3">
                            <label class="form_label">Гарчиг</label>
                            <input name="title" class="form_input" placeholder="Гарчиг">
                        </div>
                        <div class="form_item">
                            <label class="form_label">Дараалал</label>
                            <input type="number" name="sort" class="form_input" placeholder="Дугаарлалт">
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
                        <input type="file" name="highlight_image" id="highlight_image" class="form_input hidden" hidden>

                        <div class="upload_box" onclick="document.getElementById('highlight_image').click()">
                            <img id="imagePreview" src="/images/icon_upload_file.png" alt="">
                            <p>Зураг сонгох</p>
                        </div>
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
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal"
                        aria-label="Close">Болих</button>
                        <button type="submit" onclick="closeModal('sliderModal')" class="__btn btn_primary ml2" data-bs-dismiss="modal"
                        aria-label="Close">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
