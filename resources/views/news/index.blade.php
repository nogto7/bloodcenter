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
                            <div class="news_content">
                                @foreach($news as $item)
                                    <a href="news/{{ $item->slug }}" class="news_item dg gap2">
                                        <div class="item_img">
                                            <div class="img_block"><img src="{{ asset($item->highlight_image) }}" alt="" /></div>
                                        </div>
                                        <div class="">
                                            <h3>{{ $item->title }}</h3>
                                            {{ strip_tags(Str::limit($item->content, 240)) }}
                                            <em>{{ $item->publish_at->format('Y-m-d') }}</em>
                                        </div>
                                    </a>
                                @endforeach
                                {{ $news->links() }}
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