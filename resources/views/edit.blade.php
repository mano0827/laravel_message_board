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
    <script src="https://kit.fontawesome.com/ea36bd0c33.js" crossorigin="anonymous"></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
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
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
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
            <div class="create_container py-3 m-auto">
                <div class="row justify-content-between mx-4">
                    <h3 class="fw-bold col-11">??????????????????</h3>
                    <a class="col-1" href="/create">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </div>
                @error('content')
                <div class="alert alert-danger w-75 m-auto">????????????????????????</div>
                @enderror
                <form class="message_form p-1 my-2 mx-auto" action="{{ route('updateRoute') }}" method="post">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $edit_post['id'] }}">
                    <textarea class="form-control" name="content" rows="3" placeholder="?????????????????????">{{ $edit_post['content']}}</textarea>
                    <div class="text-end">
                        <form action="{{ route('destroyRoute') }}" method="post">
                            @csrf

                            <!-- ??????????????? -->
                            <button type="submit" formaction="{{ route('destroyRoute') }}" class="btn btn-outline-danger mt-1">??????</button>
                            <input type="hidden" name="post_id" value="{{ $edit_post['id'] }}">
                        </form>

                        <!-- ??????????????? -->
                        <button type="submit" formaction="{{ route('updateRoute') }}" class="btn btn-primary mt-1">??????</button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>

</html>