@extends('layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="main_slider_container">
    <div class="dg menu_slide">
        <div class="slide_wrap">
            <div class="swiper-container" id="sps">
                <div class="swiper-wrapper">
                    @foreach ($sliderNews as $slider)
                    <div class="swiper-slide slide_item {{ $slider->title ? 'is_content' : '' }}">
                        @if($slider->title)
                        <div class="slider_content">
                            <h1>{{ $slider->title }}</h1>
                            <p>{{ $slider->desc }}</p>
                            <a href="/{{ $slider->url }}" class="__btn btn_danger">Дэлгэрэнгүй</a>
                        </div>
                        @endif
                        <div class="img_block">
                            <img src="{{ $slider->highlight_image }}" alt="{{ $slider->title }}" />
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-counter"></div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next sbemt_slide_btn"></div>
                <div class="swiper-button-prev sbemt_slide_btn"></div>
            </div>
        </div>
    </div>
</div>
<section class="pb6">
    <div class="section_container">
        <h1 class="title">Мэдээ</h1>
        <div class="home_news dg gap3">
            @if($highlightNews)
            <a href="{{ url('news/'.$highlightNews->slug) }}" class="popular_news">
                <div class="item_img">
                    <div class="img_block"><img src="{{ $highlightNews->highlight_image }}" alt="{{ $highlightNews->title }}"></div>
                </div>
                <div class="item_desc">
                    <h2>{{ $highlightNews->title }}</h2>
                    <p>{{ strip_tags(Str::limit($highlightNews->content, 300)) }}</p>
                    <em><i class="fa fa-calendar-alt"></i>{{ $highlightNews->publish_at->format('Y-m-d') }}</em>
                </div>
            </a>
            @endif
            <div class="other_news">
                <ul>
                    @foreach ($homeNews as $news)
                    <li>
                        <a href="{{ url('news/'.$news->slug) }}" class="o_news_item">
                            <div class="o_item_img">
                                <div class="item_img"><div class="img_block"><img src="{{ $news->highlight_image }}" alt="{{ $news->title }}"></div></div>
                            </div>
                            <div class="item_desc">
                                <h3>{{ strip_tags(Str::limit($news->title, 80)) }}</h3>
                                <p>{{ strip_tags(Str::limit($news->content, 250)) }}</p>
                                <em>{{ $news->publish_at->format('Y-m-d') }}</em>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- <section class="">
    <div class="section_container">
        <h1>Статистик</h1>
        <div class="dg g3 gap3">
            <div class="stat_item">
                <h1>28113</h1>
            </div>
        </div>
    </div>
</section> -->
<section class="black_bg">
    <div class="section_container video_container">
        <h1>Видео</h1>
        <div class="swiper-container" id="youtubeIds">
            <div class="swiper-wrapper">
                @forelse($slideVideo ?? [] as $item)
                    <a href="https://www.youtube.com/watch?v={{ $item->url }}" class="swiper-slide slide_item" target="_blank">
                        <div class="img_block">
                            <img src="https://img.youtube.com/vi/{{ $item->url }}/hqdefault.jpg" alt="">
                        </div>
                        <div class="y_item_title">{{ $item->title }}</div>
                    </a>
                @empty
                    Видео ороогүй байна
                @endforelse
            </div>
        </div>
    </div>
</section>
<section class="pb6">
    <div class="section_container">
        @include('feedback.index')
    </div>
</section>
<script type="text/javascript" src="/js/swiper.min.js"></script>
<script type="text/javascript">
    var swiper = new Swiper('#sps', {
        slidesPerView: 1,
        watchSlidesProgress: true,
        paginationClickable: true,
        // autoplay: {
        //     delay:3e3,
        //     disableOnInteraction:false
        // },
        // speed: 1000,
        // loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
    });
    var swiper = new Swiper('#youtubeIds', {
        slidesPerView: 1,
        spaceBetween: 12,
        slidesPerGroup: 1,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction:false
        },
        speed: 1000,
        direction: 'horizontal',
        loopFillGroupWithBlank: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            767: {
                slidesPerView: 2,
                spaceBetween: 12,
                slidesPerGroup: 1,
                pagination: {
                    dynamicBullets: false
                }
            },
            991: {
                slidesPerView: 3,
                spaceBetween: 16,
                slidesPerGroup: 2
            },
            1131: {
                slidesPerView: 4,
                spaceBetween: 20,
                slidesPerGroup: 2
            }
        }
    });
    var swiper = new Swiper('#testimonial', {
        slidesPerView: 1,
        spaceBetween: 12,
        slidesPerGroup: 1,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction:false
        },
        speed: 1000,
        direction: 'horizontal',
        loopFillGroupWithBlank: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            767: {
                slidesPerView: 1,
                spaceBetween: 12,
                slidesPerGroup: 1,
                pagination: {
                    dynamicBullets: false
                }
            },
            991: {
                slidesPerView: 2,
                spaceBetween: 16,
                slidesPerGroup: 2
            },
            1131: {
                slidesPerView: 3,
                spaceBetween: 20,
                slidesPerGroup: 3
            }
        }
    });
</script>
@endsection
