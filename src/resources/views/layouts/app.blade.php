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
    <!-- resources/views/components/header.blade.php -->
    <header class="bg-black text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/">
                        <img class="h-8 w-auto" src="/images/logo.svg" alt="Coachtech Logo">
                    </a>
                </div>

                <!-- Search Bar -->
                @if (!request()->routeIs('login') && !request()->routeIs('register'))
                    <div class="hidden md:block flex-grow max-w-2xl mx-4 w-96">
                        <form action="
             {{-- {{ route('items.search') }} --}}
           " method="GET">
                            <input type="text" name="keyword" placeholder="なにをお探しですか？"
                                class="w-full px-4 py-2 rounded text-black text-sm h-10">
                        </form>
                    </div>
                @endif


                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4 flex-shrink-0">
                    @auth
                        <a href="
                  {{-- {{ route('logout') }} --}}
                "
                            class="hover:underline">ログアウト</a>
                        <a href="
                  {{-- {{ route('mypage') }} --}}
                "
                            class="hover:underline">マイページ</a>
                    @else
                        @if (!request()->routeIs('login') && !request()->routeIs('register'))
                            <a href="
                    {{-- {{ route('login') }} --}}
                  "
                                class="hover:underline">ログイン</a>
                            <a href="
                    {{-- {{ route('mypage') }} --}}
                  "
                                class="hover:underline">マイページ</a>
                        @endif
                    @endauth

                    <a href="
                {{-- {{ route('items.create') }} --}}
              "
                        class="bg-white text-black px-3 py-1 rounded hover:bg-gray-200">出品</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-white focus:outline-none">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Content -->
        <div id="mobile-menu" class="hidden md:hidden bg-black text-white px-4 py-4 space-y-4">

            {{-- 検索フォーム --}}
            @if (!request()->routeIs('login') && !request()->routeIs('register'))
                <form action="
        {{-- {{ route('items.search') }} --}}
        " method="GET" class="max-w-sm mx-auto">
                    <input type="text" name="keyword" placeholder="なにをお探しですか？"
                        class="w-full px-4 py-2 rounded text-black text-sm">
                </form>
            @endif

            {{-- 認証状態別リンク --}}
            <div class="text-center space-y-2">
                @auth
                    <a href="
            {{-- {{ route('logout') }} --}}
            " class="block">ログアウト</a>
                    <a href="
            {{-- {{ route('mypage') }} --}}
            " class="block">マイページ</a>
                @else
                    @if (!request()->routeIs('login') && !request()->routeIs('register'))
                        <a href="
                {{-- {{ route('login') }} --}}
                " class="block">ログイン</a>
                        <a href="
                {{-- {{ route('mypage') }} --}}
                " class="block">マイページ</a>
                    @endif
                @endauth
            </div>

            {{-- 出品ボタン --}}
            <div class="max-w-sm mx-auto">
                <a href="
        {{-- {{ route('items.create') }} --}}
        "
                    class="block w-full bg-white text-black px-4 py-2 rounded text-center hover:bg-gray-200">出品</a>
            </div>
        </div>



        <script>
            document.getElementById('mobile-menu-button')?.addEventListener('click', () => {
                document.getElementById('mobile-menu')?.classList.toggle('hidden');
            });
        </script>
    </header>
    <main >
        @yield('content')
    </main>
</body>

</html>
