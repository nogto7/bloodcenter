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
                                {{-- Хэрэв sub menu бол parent нэрийг гаргах --}}
                                @if($files->isEmpty())
                                <p>Файл олдсонгүй.</p>
                                @else
                                <div class="table_wrap">
                                    <table class="table_content">
                                        <thead>
                                            <tr>
                                                <th style="width: 40px">#</th>
                                                <th>Нэр</th>
                                                <th>Дугаар</th>
                                                <th style="width: 110px">Огноо</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($files as $key => $file)
                                            <tr>
                                                <td>{{ 1 + $key }}</td>
                                                {{-- <td><a href="{{ asset('storage/' . $file->path) }}" target="_blank">
                                                    {{ $file->title }}
                                                </a></td> --}}
                                                <td><a href="{{ route('file.show', $file->id) }}">
                                                    {{ $file->title }}
                                                </a></td>
                                                <td>@if($file->number){{ $file->number }}@endif</td>
                                                <td>@if($file->date){{ $file->date }}@endif</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
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