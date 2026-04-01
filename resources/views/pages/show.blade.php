@extends('layout')
@section('content')
<div class="page_wrapper">
    <div class="section_container">
        <div class="section_line">
            <div class="page_wrap department">
                @if(!empty($department) && !empty($department->cover_image))
                <div class="img_block"><img src="{{ asset($department->cover_image) }}" alt="{{ $department->name }}"></div>
                <div class="show_page_wrap">
                    <div class="show_content">
                        <h1>{{ $department->name }}</h1>
                        <div class="show_text">{!! $department->description !!}</div>
                        <div class="employees">
                            @if($department && $department->employees->count())
                                <div class="employee_list">
                                    @foreach($department->employees as $emp)
                                        <div class="employee_item">
                                            <div class="emp_img img_block">
                                                @if($emp->photo)
                                                <img src="{{ asset($emp->photo) }}" alt="{{ $emp->fullname }}">
                                                @endif
                                            </div>
                                            <div class="emp_info">
                                                <h4>{{ $emp->fullname }}</h4>
                                                <p>{{ $emp->position }}</p>
                                            </div>
                                            <span style="background-color: {{ $department->color }}"></span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty_text">Ажилтан бүртгэгдээгүй байна</div>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <div class="empty_text">Алба оруулаагүй байна</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection