{{--
    Рекурсив <option> жагсаалт — үндсэн цэс, дэд цэс, дэдийн дэд цэс бүгдийг гүн харуулна.
    Параметрүүд:
      $items    - Menu collection
      $prefix   - түвшин заах угтвар (— )
      $selected - сонгогдсон menu_id (заавал биш)
--}}
@foreach($items as $menu)
    <option value="{{ $menu->id }}" {{ (string)($selected ?? '') === (string)$menu->id ? 'selected' : '' }}>{{ $prefix ?? '' }}{{ $menu->title }}</option>
    @if($menu->children->isNotEmpty())
        @include('admin.partials.menu_options', ['items' => $menu->children, 'prefix' => ($prefix ?? '') . '— ', 'selected' => $selected ?? null])
    @endif
@endforeach
