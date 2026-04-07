@extends('layout')
@section('content')
<div class="page_wrapper">
    <div class="section_container">
        <div class="section_line">
            <div class="page_wrap department">
                @if(!empty($department) && !empty($department->cover_image))
                <div class="img_block"><img src="{{ asset($department->cover_image) }}" alt="{{ $department->name }}"></div>
                <div class="show_page_wrap">
                    <div class="accordion" id="accordionExample">
                    @foreach($menu->groups as $group)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $group->id }}">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $group->id }}">
                                    {{ $group->title }}
                                </button>
                            </h2>
                    
                            <div id="collapse{{ $group->id }}"
                                    class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                    
                                <div class="accordion-body">
                    
                                    @foreach($group->items as $item)
                    
                                        {{-- TEXT --}}
                                        @if($item->type == 'text')
                                            <p>{!! $item->content !!}</p>
                                        @endif
                    
                                        {{-- IMAGE --}}
                                        @if($item->type == 'image')
                                            <img src="{{ asset('storage/'.$item->file_path) }}"
                                                    style="max-width:100%;">
                                        @endif
                    
                                        {{-- FILE --}}
                                        @if($item->type == 'file')
                                            <a href="{{ asset('storage/'.$item->file_path) }}"
                                                target="_blank">
                                                📄 {{ $item->title }}
                                            </a>
                                        @endif
                    
                                    @endforeach
                    
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    </div>
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