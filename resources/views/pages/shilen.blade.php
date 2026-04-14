@extends('layout')
@section('content')
<div class="page_wrapper">
    <div class="section_container">
        <div class="section_line">
            <div class="page_wrap">
                <div class="dg">
                    <h1 class="title">
                        @if(isset($menu->title))
                        {{ $menu->title }}
                        @endif
                    </h1>
                    <div class="dg page_grid gap3">
                        <div class="page_content">
                            <div class="shilen_wrap">
                            @foreach($menuItems as $menu)
                                <div class="shilen_item">
                                    <div class="shilen_head"><h2>{{ $menu->title }}</h2></div>
                                    <div class="shilen_content">
                                        @foreach($items->where('group_id', $menu->id) as $item)
                                            @if($item->type === 'link')
                                            <a href="{{ $item->link }}"><span class="fa fa-link"></span>{{ $item->title }}</a>
                                            @elseif ($item->type === 'file')
                                            <a href="{{ route('file.show', $item->file_id) }}"><span class="fa fa-file"></span>{{ $item->file->title }}</a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        <div class="page_sidebar">
                            @include('components.latestNews')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
