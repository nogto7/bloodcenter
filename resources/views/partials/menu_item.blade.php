{{--
    Рекурсив цэсний элемент.
    Параметрүүд:
      $item  - Menu загвар
      $depth - 0 = дээд цэс, 1 = дэд цэс, 2+ = дэдийн дэд цэс
--}}
@php
    $children    = $item->children;
    $hasChildren = $children->isNotEmpty();

    if ($depth === 0) {
        $liClass   = $hasChildren ? 'is_sub' : '';
        $wrapClass = 'sub_menu';
    } else {
        $liClass   = $hasChildren ? 'is_sub_sub' : '';
        $wrapClass = 'sub_sub_menu';
    }

    $href = $item->type === 'url'
        ? ($item->url ?: '#')
        : '/' . ltrim($item->url ?? '', '/');
@endphp
<li class="{{ $liClass }}">
    <a href="{{ $href }}" {{ $item->id == 5 ? 'target=_blank' : '' }}>{{ $item->title }}</a>
    @if($hasChildren)
        <div class="{{ $wrapClass }}">
            <ul class="{{ $wrapClass }}">
                @foreach($children as $child)
                    @include('partials.menu_item', ['item' => $child, 'depth' => $depth + 1])
                @endforeach
            </ul>
        </div>
    @endif
</li>
