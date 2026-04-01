@extends('layout')

@section('content')
<div class="page_wrapper">
    <div class="section_container">
        <div class="section_line">
            <div class="page_wrap">
                <div class="dg">
                    <h1 class="title">
                        {{ $file->title }}
                    </h1>
                    <div class="dg page_grid gap3">
                        <div class="page_content">
                            <div style="margin-bottom:15px;">
                                @if($file->number)
                                    <strong>Дугаар:</strong> {{ $file->number }} &nbsp;
                                @endif

                                @if($file->date)
                                    <strong>Огноо:</strong> {{ $file->date }}
                                @endif
                            </div>
                            <div style="height:75vh;">
                                <iframe
                                    src="{{ asset('storage/' . $file->path) }}"
                                    width="100%"
                                    height="100%"
                                    style="border:1px solid #ddd;"
                                ></iframe>
                            </div>
                            <div class="mt2 dcsb">
                                <a href="{{ asset('storage/' . $file->path) }}" download>📥 Файл татах</a>
                                <p>{{ number_format($file->views) }} удаа үзсэн</p>
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
