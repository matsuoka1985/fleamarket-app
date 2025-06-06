<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <header class="bg-black text-white relative z-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- ロゴ -->
                <div class="">
                    <a href="/">
                        <img class="h-8 w-auto" src="/images/logo.svg" alt="Coachtech Logo">
                    </a>
                </div>

                <!-- ログイン画面、サインアップ画面、メール確認画面においては、表示しない -->
                @if (!request()->routeIs(['login', 'register', 'verification.notice']))
                    <!-- 検索フォーム -->
                    <div class="hidden md:block flex-grow max-w-96 mx-4 ">
                        {{-- <div class="hidden md:block max-w-96 flex-grow "> --}}
                        <form action="{{ route('items.index') }}" method="GET">
                            @if (request('tab'))
                                <input type="hidden" name="tab" value="{{ request('tab') }}">
                            @endif
                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                                placeholder="なにをお探しですか？" class="w-full px-4 py-2 rounded text-black text-sm h-10">
                        </form>
                    </div>
                    <!-- デスクトップメニュー -->
                    <div class="hidden md:flex items-center space-x-4 flex-shrink-0">
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="hover:underline bg-transparent text-white">
                                    ログアウト
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="hover:underline">ログイン</a>
                        @endauth
                        <a href="{{ route('users.show') }}" class="hover:underline">マイページ</a>
                        {{-- 出品ボタン --}}
                        <a href="{{ route('items.create') }}"
                            class="bg-white text-black px-3 py-1 rounded hover:bg-gray-200">出品</a>
                    </div>
                    <!-- ハンバーガーメニュー -->
                    <div class="md:hidden">
                        <button id="mobile-menu-button" class="text-white focus:outline-none">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                @endif


            </div>
        </div>

        <!-- モバイル用ヘッダー -->
        <div id="mobile-menu"
            class="hidden absolute top-16 left-0 w-full bg-black text-white px-4 py-6 space-y-4 z-50
                   transition-all duration-300 transform -translate-y-4 opacity-0 pointer-events-none">
            <!-- ログイン画面、サインアップ画面、メール確認画面においては、表示しない -->
            @if (!request()->routeIs(['login', 'register', 'verification.notice']))
                <!-- 検索フォーム -->
                <form action="{{ route('items.index') }}" method="GET">
                    @if (request('tab'))
                        <input type="hidden" name="tab" value="{{ request('tab') }}">
                    @endif
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？"
                        class="w-full px-4 py-2 rounded text-black text-sm">
                </form>
                <div class="flex flex-col space-y-4">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-center bg-white text-black px-3 py-2 rounded hover:bg-gray-200">
                                ログアウト
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="block w-full text-center bg-white text-black px-3 py-2 rounded hover:bg-gray-200">
                            ログイン
                        </a>
                    @endauth
                    <a href="{{ route('users.show') }}"
                        class="block w-full text-center bg-white text-black px-3 py-2 rounded hover:bg-gray-200">
                        マイページ
                    </a>
                </div>

                {{-- 出品ボタン --}}
                <a href="{{ route('items.create') }}"
                    class="block w-full text-center bg-white text-black px-3 py-2 rounded hover:bg-gray-200">出品</a>
            @endif

        </div>

        <script>
            const menuBtn = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');

            menuBtn?.addEventListener('click', () => {
                const isVisible = !menu.classList.contains('hidden');

                if (isVisible) {
                    menu.classList.add('opacity-0', '-translate-y-4');
                    menu.classList.remove('opacity-100', 'translate-y-0');
                    setTimeout(() => {
                        menu.classList.add('hidden', 'pointer-events-none');
                    }, 200);
                } else {
                    menu.classList.remove('hidden', 'pointer-events-none');
                    setTimeout(() => {
                        menu.classList.remove('opacity-0', '-translate-y-4');
                        menu.classList.add('opacity-100', 'translate-y-0');
                    }, 10);
                }
            });

            // ブラウザ幅が広くなった際にメニューを非表示にする
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    menu.classList.add('hidden', 'opacity-0', '-translate-y-4', 'pointer-events-none');
                    menu.classList.remove('opacity-100', 'translate-y-0');
                }
            });
        </script>

    </header>

    @yield('content')
    @stack('scripts')
</body>

</html>
