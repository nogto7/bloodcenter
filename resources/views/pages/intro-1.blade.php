@extends('layout')
@section('content')
<div class="page_header">
    <div class="section_container">
        <h2>Тусгай зөвшөөрөл</h2>
    </div>
</div>
<div class="section_container">
    <div class="page_wrap">
        <div class="section_container">
            <div class="dg">
                <div class="dg page_grid gap3">
                    <div class="page_content">
                        <div class="page_text">
                            <div class="img_block"><img src="{{ asset('storage/Tusgai-zovshoorol-2024-1-816x1154.jpg') }}" alt=""></div>
                            <div class="img_block"><img src="{{ asset('storage/Tusgai-zovshoorol-2024-2-816x1154.jpg') }}" alt=""></div>
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