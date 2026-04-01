@extends('admin.layout')
@section('content')
<div class="admin_root folder_header">
    <div class="n_breadcrumb dfc">
        <ul>
            <li><a href=""><span class="bread_home"></span>Эхлэл</a></li>
            <li><p>Санал хүсэлтийн жагсаалт</p></li>
        </ul>
    </div>
</div>
<div class="admin_file_wrap">
    <div class="file_content">
        <h2>Санал хүсэлт</h2>
        <div class="table_wrap">
            <table class="table_content">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Нэр</th>
                        <th>Утасны дугаар</th>
                        <th>И-мэйл хаяг</th>
                        <th>Төрөл</th>
                        <th>Хэнд хандсан</th>
                        <th>Мэдээлэл</th>
                        <th>Үйлдэл</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($feedbackList as $key => $item)
                        <tr>
                            <td>{{ $feedbackList->firstItem() + $key }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->feedback_type }}</td>
                            <td>{{ $item->feedback_position }}</td>
                            <td>{{ strip_tags(Str::limit($item->message, 240)) }}</td>
                            <td>
                                <form action="{{ route('admin.feedback.destroy', ['feedback' => $item->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="__btn btn_primary" onclick="return confirm('Та устгахдаа итгэлтэй байна уу?')">Устгах</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Мэдээ олдсонгүй</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
