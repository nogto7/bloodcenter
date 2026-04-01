@extends('admin.layout')
@section('content')
<div class="admin_root folder_header">
    <div class="dcsb">
        <div class="n_breadcrumb dfc">
            <ul>
                <li><a href=""><span class="bread_home"></span>Эхлэл</a></li>
                <li><p>Youtube видео жагсаалт</p></li>
            </ul>
        </div>
        <div class="fline_header dfc">
            <div class="file_options dfc">
                <button class="f_f_button f_edit" id="btnRename" disabled><span></span>Солих</button>
                <button class="f_f_button f_delete" id="btnDelete" disabled><span></span>Устгах</button>
            </div>
            <div class="folder_file dfc">
                <button class="f_f_button f_file" data-bs-toggle="modal" data-bs-target="#addVideo"><span></span>Видео нэмэх</button>
            </div>
        </div>
    </div>
</div>
<div class="admin_file_wrap">
    <div class="file_content">
        <h2>Youtube видео</h2>
        <div class="table_wrap">
            <table class="table_content">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Зураг</th>
                        <th>Гарчиг</th>
                        <th>Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($videoList as $key => $item)
                    <tr>
                        <td style="width: 40px">{{ $videoList->firstItem() + $key }}</td>
                        <td style="width: 130px"><div class="img_block"><img src="http://img.youtube.com/vi/{{ $item->url }}/hqdefault.jpg" alt=""></div></td>
                        <td>{{ $item->title }}</td>
                        <td style="width: 160px">
                            <div class="dfc">
                                <a href="{{ route('admin.video.edit', $item) }}" class="f_f_button f_edit"><span></span>Засах</a>
                                <form action="{{ route('admin.video.destroy', $item) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="f_f_button f_delete ml1" onclick="return confirm('Та устгахдаа итгэлтэй байна уу?')"><span></span>Устгах</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">Мэдээлэл олдсонгүй</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $videoList->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="addVideo" tabindex="-1" aria-labelledby="transfusionModal" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="core_modal">
            <div class="modal_header">
                <h2>Видео нэмэх</h2>
            </div>
            <form id="videoForm">
                @csrf
                <div class="modal_main">
                    <div class="form_item">
                        <label class="form_label">Гарчиг</label>
                        <input name="title" class="form_input" placeholder="Гарчиг">
                    </div>
                    <div class="form_item">
                        <label class="form_label">Youtube-n URL id</label>
                        <input name="url" class="form_input" placeholder="Мэдээний линк">
                    </div>
                </div>
                <div class="modal_footer">
                    <div class="dfc jce">
                        <button type="button" class="__btn" data-bs-dismiss="modal"
                        aria-label="Close">Болих</button>
                        <button type="submit" onclick="closeModal('videoModal')" class="__btn btn_primary ml2" data-bs-dismiss="modal"
                        aria-label="Close">Хадгалах</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
