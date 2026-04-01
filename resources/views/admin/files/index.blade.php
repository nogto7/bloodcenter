@extends('admin.layout')

@section('content')
<div class="ad_main_head dcsb">
    <h3>File Manager</h3>
    <a href="{{ route('admin.files.create') }}" class="__btn green_btn">Файл нэмэх</a>
</div>
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
