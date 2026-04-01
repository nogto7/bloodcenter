@extends('admin.layout')

@section('content')
    <h1>{{ $file->title }}</h1>

    @if(Str::contains($file->mime_type,'pdf'))
        <iframe
            src="{{ asset('storage/'.$file->path) }}"
            width="100%"
            height="800">
        </iframe>
    @else
        <a href="{{ asset('storage/'.$file->path) }}" target="_blank">
            Файл татах
        </a>
    @endif
    @endsection
