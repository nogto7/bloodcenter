{{--
    Admin цэсний жагсаалтын рекурсив мөр.
    Параметрүүд:
      $menu  - Menu загвар
      $index - дугаарлалт
      $depth - үүрлэлтийн гүн (0 = дээд цэс)
--}}
<tr>
    <td style="width: 40px">{{ $index + 1 }}</td>
    <td>{!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth) !!}{{ $depth > 0 ? '└ ' : '' }}{{ $menu->title }}</td>
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
                @foreach($menu->children as $childIndex => $child)
                    @include('admin.menus._row', ['menu' => $child, 'index' => $childIndex, 'depth' => $depth + 1])
                @endforeach
            </table>
        </td>
    </tr>
@endif
