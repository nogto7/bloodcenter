@extends('layout')
@section('content')
<div class="page_header">
    <div class="section_container">
        <div class="dg page_h_grid">
            <a href="/news" class="back_btn"><span></span></a>
            <h2>{{ $news->title }}</h2>
        </div>
    </div>
</div>
<div class="section_container">
    <div class="page_wrap">
        <div class="section_container">
            <div class="dg">
                <div class="dg page_grid gap2">
                    <div class="page_content">
                        <div class="news_detail">
                            {{-- <h1 class="title">{{ $news->title }}</h1> --}}
                            <div class="news_content">
                                @if($news->highlight_image)
                                <div class="hightlight_img">
                                    <div class="img_block"><img src="{{ asset($news->highlight_image) }}" alt="" /></div>
                                </div>
                                @endif
                                <em><i class="fa fa-calendar-alt"></i>{{ $news->publish_at->format('Y-m-d') }}</em>
                                {!! $news->content !!}
                            </div>
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
@endsection