<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <a class=" text-dark nav-link active" aria-current="page"
                       href="{{ url('/')}}"><img src="{{asset('assets/images/logo2.png')}}" width="120" height="23" class="d-inline-block align-right"></a>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>


                    <form method="post" action="{{url('/search')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="text" placeholder="Search..." name="search" size="30">
                        <button onclick="location.href='/search'" type="submit" style="margin-left: -3px; margin-top: -15px; border: none; width:30px; height:30px; border-radius: 5px; ">
                            <img src="{{asset('assets/images/search.png')}}" width="20" height="20" class="d-inline-block align-right">
                        </button>

                    </form>

                    @if (Auth::check())
                    <form method="get" action="{{url('/requests')}}">
                        @csrf
                        <input type="image" name="imgbtn" style="margin-left:240px; margin-right: -520px; width: 30px; height: 30px"
                               src="{{asset('assets/images/requests.png')}}"  alt="Tool Tip">
                    </form>

                        <form method="get" action="{{url('/newsfeed')}}">
                            @csrf
                            <input type="image" name="imgbtn" style="margin-left:213px; margin-right: -520px; width: 20px; height: 20px"
                                   src="{{asset('assets/images/NewsFeed.png')}}"  alt="Tool Tip">
                        </form>
                    @endif

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{url('/home')}}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('profile-form').submit();">
                                        {{ __('Profile') }}
                                    </a>

                                    <form id="profile-form" action="{{url('/home')}}" method="get" class="d-none">
                                        @csrf
                                    </form>

                                    <a class="dropdown-item" href="{{url('/newsfeed')}}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('newsfeed-form').submit();">
                                        {{ __('Newsfeed') }}
                                    </a>

                                    <form id="newsfeed-form" action="{{url('/newsfeed')}}" method="get" class="d-none">
                                        @csrf
                                    </form>

                                    <a class="dropdown-item" href="{{url('/settings')}}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('settings-form').submit();">
                                        {{ __('Settings') }}
                                    </a>

                                    <form id="settings-form" action="{{url('/settings')}}" method="get" class="d-none">
                                        @csrf
                                    </form>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
