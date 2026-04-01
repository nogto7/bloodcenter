<html lang="mn">
<head>
    <meta name="keywords" content="">
    <meta charset="utf-8">
    <meta name="author" content="nogto">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1" />
    <title>Нүүр | Цус сэлбэлт судлалын үндэсний төв</title>
    <link href="/images/favicon.png" rel="shortcut icon" type="image/png" />
    <link rel="stylesheet" type="text/css" href="/css/swiper.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body>
    <header class="header_wrap">
        <div class="header_topbar">
            <div class="section_container dcsb">
                <div class="dfc">
                    <div class="ht_item">Холбогдох: 70112857</div>
                    <div class="ht_item">Бямба, Ням гарагт амарна.</div>
                </div>
                <div class="dfc">
                    <div class="socials">
                        <a href="https://www.facebook.com/profile.php?id=100064388001679" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                        <a href="https://www.youtube.com/@BloodcenterMongolia-le5ti" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header_main">
            <div class="section_container">
                <div class="dcsb">
                    <div class="dfc header_lm">
                        <div class="logo">
                            <a href="/" class="dg grid_logo"><span></span><div class="slogan"><p>Цус сэлбэлт судлалын <br>үндэсний төв</p></div></a>
                        </div>
                    </div>
                    <div class="menu_navigation dcsb">
                        <div class="menu_wrap">
                            <ul>
                                @foreach($menus as $menu)
                                <li class="{{ count($menu->children) ? 'is_sub' : '' }}">
                                    <a href="/{{ $menu->url }}" {{ $menu->id == 5 ? 'target=_blank' : '' }}>{{ $menu->title }}</a>
                                    @if(count($menu->children))
                                    <div class="sub_menu">
                                        <ul class="sub_menu">
                                            @foreach($menu->children as $child)
                                                <li>
                                                    <a href="/{{ $child->url ?? '#' }}">{{ $child->title }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="main_wrap">
        @yield('content')
    </div>
    <footer class="footer_wrap">
        <div class="section_container">
            <div class="footer_bottom dcsb">
                <p>© 2025{{ date('Y') != 2025 ? ' - ' . date('Y') : '' }}. Цус сэлбэлт судлалын үндэсний төв</p>
                <p>Зохиогчийн эрх хуулиар хамгаалагдсан. Мэдээлэл хуулбарлах хориотой.</p>
            </div>
        </div>
    </footer>
    @include('components.toast')

    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
</body>
</html>
