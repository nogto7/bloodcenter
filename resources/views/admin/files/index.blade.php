@extends('admin.layout')

@section('content')
@foreach($folders as $folder)
    <h3>📁 {{ $folder->name }}</h3>
    <ul>
        @foreach($folder->files as $file)
            <li>
                📄 <a href="{{ route('admin.files.show',$file) }}">
                    {{ $file->title }}
                </a>
            </li>
        @endforeach
    </ul>
@endforeach
@endsection
