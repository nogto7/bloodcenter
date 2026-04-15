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
                            {!! $news->content !!}
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
