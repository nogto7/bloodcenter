<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="keywords" content="">
    <meta charset="utf-8">
    <meta name="author" content="nogto">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
    <title>Adminpanel | Эрүүл мэндийг дэмжих жил 2027</title>
    <link href="/images/favicon.png" rel="shortcut icon" type="image/png" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <script type="text/javascript" src="/js/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="adminpanel">
    <div class="admin_wrap">
        <div class="admin_header">
            <div class="dcsb">
                <div class="admin_logo">
                    <span></span>
                    <div class="slogan"><p>Цус сэлбэлт судлалын үндэсний төв</p></div>
                </div>
                <div class="admin_hr">
                    <ul>
                        <li><a href="/" target="_blank">Сайт харах</a></li>
                        @if(auth()->check())
                            <li>
                                <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Гарах
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="admin_content admin_grid dg">
            <div class="admin_sidebar">
                <div class="ad_sidebar">
                    <ul>
                        {{-- <li><a href="/admin/users">Admin</a></li> --}}
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <li><a href="/admin/menus" class="icon_sidebar icon_menu"><span></span><i>Цэс</i></a></li>
                        <li><a href="/admin/department" class="icon_sidebar icon_news"><span></span><i>Алба</i></a></li>
                        <li><a href="/admin/groups" class="icon_sidebar icon_news"><span></span><i>Шил ажиллагаа</i></a></li>
                        @endif
                        @if(auth()->check() && auth()->user()->role === 'admin' || (auth()->check() && auth()->user()->role === 'editor') || (auth()->check() && auth()->user()->role === 'publisher'))
                        <li><a href="/admin/news" class="icon_sidebar icon_news"><span></span><i>Мэдээлэл</i></a></li>
                        @endif
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <li><a href="/admin/slider" class="icon_sidebar icon_slider"><span></span><i>Слайдер</i></a></li>
                        <li><a href="/admin/faq" class="icon_sidebar icon_slider"><span></span><i>Түгээмэл асуулт хариулт</i></a></li>
                        <li><a href="/admin/video" class="icon_sidebar icon_video"><span></span><i>Видео</i></a></li>
                        <li><a href="/admin/feedback" class="icon_sidebar icon_feedback"><span></span><i>Санал хүсэлт</i></a></li>
                        <li><a href="/admin/folders" class="icon_sidebar icon_folder"><span></span><i>Файлын удирдлага</i></a></li>
                        <li><a href="/admin/users" class="icon_sidebar icon_users"><span></span><i>Хэрэглэгч</i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="admin_main">
                <div class="ad_content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/main.js"></script>
</body>
</html>
