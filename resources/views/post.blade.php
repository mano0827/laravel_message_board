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
                    <h3 class="fw-bold col-11">投稿内容</h3>
                    <a class="col-1" href="/create">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </div>

                <div class="message_box p-1 my-2 mx-auto">

                    <!-- 投稿詳細 -->
                    <div class="post_box m-1">
                        <div class="post_info">
                            <!-- ユーザ名 -->
                            <a href="/account/{{ $post_content['user_id'] }}" class="message_content large mx-1 text-decoration-none">{{ $posts[0]['name'] }}</a>
                            <!-- 投稿時間 -->
                            <p class="d-inline">{{ $post_content['created_at']->diffForHumans() }}</p>
                        </div>

                        <!-- 投稿内容 -->
                        <p class="message_content d-block mx-1 my-0 text-decoration-none">{{ $post_content['content'] }}</p>
                        <div class="text-end me-2">
                            @if($post_content['user_id'] === \Auth::id())
                            <a href="/edit/{{ $post_content['id'] }}" class="message_content d-inline mx-1 text-decoration-none">
                                <!-- 設定ボタン -->
                                <i class="fa-solid fa-wrench"></i>
                            </a>
                            @endif
                            <!-- 返信一覧ボタン -->
                            <a href="/post/{{ $post_content['id'] }}" class="text-decoration-none comment_count">
                                <i class="fa-regular fa-comment-dots ms-1"></i>
                                <!-- コメント数表示のための初期値設定 -->
                                @php
                                $comment_count = 0;
                                @endphp
                                @foreach($comments as $comment)
                                <!-- 返信数表示ロジック -->
                                    @if($comment['post_id'] === $post_content['id'])
                                        @php
                                        $comment_count = $comment_count + 1;
                                        @endphp
                                    @endif
                                @endforeach
                                +{{ $comment_count }}

                                <!-- 返信ボタン -->
                                <a class="col-1" href="/reply/{{ $post_content['id'] }}">
                                    @csrf
                                    <input type="hidden" name="post_content" value="{{ $post_content['id'] }}">
                                    <i class="fa-solid fa-reply ms-1"></i>
                        </div>
                        </a>
                    </div>


                    <!-- 返信一覧 -->
                    <div class="comments_box mx-auto my-3">
                        @foreach($comments as $comment)
                        @if($comment['post_id'] === $post_content['id'])
                        <div class="comment_box mx-auto my-2">
                            <!-- ユーザ名 -->
                            <a href="/account/{{ $comment['user_id'] }}" class="comment_user mx-1 text-decoration-none">{{$comment['name']}}</a>
                            <!-- 返信時間 -->
                            <p class="d-inline">{{ $comment['created_at']->diffForHumans() }}</p>

                            <!-- 返信内容 -->
                            <p class="comment_content d-block mx-1 my-0 text-decoration-none">{{$comment['content']}}</p>
                        </div>
                        @endif
                        @endforeach
                        <div class="text-end me-2">
                        </div>
                    </div>

                </div>
        </main>
    </div>
</body>

</html>