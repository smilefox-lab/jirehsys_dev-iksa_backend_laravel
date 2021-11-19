<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="canonical" href="{{ url('/') }}">
    <meta http-equiv="content-language" content="en">

    {!! Theme::header() !!}

    <!-- Fonts-->
    <link href="https://fonts.googleapis.com/css?family={{ theme_option('primary_font', 'Nunito Sans') }}:300,600,700,800" rel="stylesheet" type="text/css">
    <!-- CSS Library-->

    <style>
        :root {
            --primary-color: {{ theme_option('primary_color', '#1d5f6f') }};
            --primary-color-hover: {{ theme_option('primary_color_hover', '#063a5d') }};
        }

        body {
            font-family: '{{ theme_option('primary_font', 'Nunito Sans') }}', sans-serif !important;
        }
    </style>
</head>
<body>

<div class="bravo_topbar">
    <div class="container-fluid w90">
        <div class="row">
            <div class="col-12">
                <div class="content">
                    <div class="topbar-left d-none d-sm-block">
                        <div class="top-socials">
                            <a href="{{ theme_option('facebook') }}" title="Facebook" class="fab fa-facebook-f"></a>
                            <a href="{{ theme_option('twitter') }}" title="Twitter" class="fab fa-twitter"></a>
                            <a href="{{ theme_option('youtube') }}" title="Youtube" class="fab fa-youtube"></a>
                        </div>
                        <span class="line"></span>
                        <a href="mailto:{{ theme_option('email') }}">{{ theme_option('email') }}</a>
                    </div>
                    <div class="topbar-right">
                        {!! Theme::partial('language-switcher') !!}
                        @if (is_plugin_active('vendor'))
                            <ul class="topbar-items">
                                @if (Auth::guard('vendor')->check())
                                    <li class="login-item"><a href="{{ route('public.vendor.dashboard') }}" rel="nofollow"><i class="fas fa-user"></i> <span>{{ Auth::guard('vendor')->user()->getFullName() }}</span></a></li>
                                    <li class="login-item"><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" rel="nofollow"><i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}</a></li>
                                @else
                                    <li class="login-item">
                                        <a href="{{ route('public.vendor.login') }}"><i class="fas fa-sign-in-alt"></i>  {{ __('Login') }}</a>
                                    </li>
                                    <li class="login-item">
                                        <a href="{{ route('public.vendor.register') }}"><i class="fas fa-user-plus"></i> {{ __('Register') }}</a>
                                    </li>
                                @endif
                            </ul>
                            @auth('vendor')
                                <form id="logout-form" action="{{ route('public.vendor.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<header class="topmenu bg-light">
    <div class="container-fluid w90">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    @if (theme_option('logo'))
                        <a class="navbar-brand" href="{{ route('public.single') }}">
                            <img src="{{ get_image_url(theme_option('logo')) }}"
                                 class="logo" height="40" alt="{{ theme_option('site_name') }}">
                        </a>
                    @endif
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fas fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        {!!
                            Menu::renderMenuLocation('main-menu', [
                                'options' => ['class' => 'navbar-nav justify-content-end'],
                                'view'    => 'main-menu',
                            ])
                        !!}
                        @if (is_plugin_active('vendor'))
                            <a class="btn btn-primary add-property" href="{{ route('public.vendor.properties.index') }}">
                                <i class="fas fa-plus-circle"></i> {{ __('Add Property') }}
                            </a>
                        @endif
                    </div>
                </nav>
            </div>
        </div>
    </div>
    @php
        $page = Theme::get('page');
    @endphp
    @if (url()->current() == route('public.single') || ($page && $page->template === 'homepage'))
        <div class="home_banner" style="background-image: url({{ theme_option('home_banner') ? get_image_url(theme_option('home_banner')) : Theme::asset()->url('images/banner.jpg') }})">
            <div class="topsearch">
                @if (theme_option('home_banner_description'))<h1 class="text-center text-white mb-4" style="font-size: 36px; font-weight: 600;">{{ theme_option('home_banner_description') }}</h1>@endif
                <form action="{{ route('public.projects') }}" method="GET" id="frmhomesearch">
                    <div class="typesearch" id="hometypesearch">
                        <a href="javascript:void(0)" class="active" rel="project" data-url="{{ route('public.projects') }}">{{ __('Projects') }}</a>
                        <a href="javascript:void(0)" rel="sale" data-url="{{ route('public.properties') }}">{{ __('Sale') }}</a>
                        <a href="javascript:void(0)" rel="rent" data-url="{{ route('public.properties') }}">{{ __('Rent') }}</a>
                    </div>
                    <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><img src="{{ Theme::asset()->url('images/search_icon.png') }}" alt="search"></span>
                        </div>
                        <input type="hidden" name="type" value="project" id="txttypesearch">
                        <input type="text" class="form-control" name="k"
                               placeholder="{{ __('Enter keyword...') }}" id="txtkey" autocomplete="off">
                        <div class="input-group-append">
                            <button class="btn btn-orange" type="submit">{{ __('Search') }}</button>
                        </div>
                    </div>
                    <div class="listsuggest stylebar">

                    </div>
                </form>
            </div>
        </div>
        </div>
    @endif
</header>
