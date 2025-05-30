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
                @if (!request()->routeIs('login') && !request()->routeIs(['register', 'verification.notice']))
                    <div class="hidden md:block flex-grow max-w-2xl mx-4 w-96">
                        <form action="{{ route('items.index') }}" method="GET">
                            @if (request('tab'))
                                <input type="hidden" name="tab" value="{{ request('tab') }}">
                            @endif
                            <input type="text" name="keyword" value="{{ request('keyword') }}"
                                placeholder="なにをお探しですか？" class="w-full px-4 py-2 rounded text-black text-sm h-10">
                        </form>


                    </div>
                @endif


                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4 flex-shrink-0">
                    @auth
                        @if (!request()->routeIs(['verification.notice']))
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="hover:underline bg-transparent text-white">
                                    ログアウト
                                </button>
                            </form>
                            <a href="
                          {{-- {{ route('mypage') }} --}}
                        "
                                class="hover:underline">マイページ</a>
                        @endif
                    @else
                        @if (!request()->routeIs('login') && !request()->routeIs(['register', 'verification.notice']))
                            <a href="
                    {{ route('login') }}
                  "
                                class="hover:underline">ログイン</a>
                            <a href="
                    {{-- {{ route('mypage') }} --}}
                  "
                                class="hover:underline">マイページ</a>
                        @endif
                    @endauth
                    @if (!request()->routeIs(['register', 'verification.notice', 'login']))
                        {{-- 出品ボタン --}}
                        <a href="
                {{-- {{ route('items.create') }} --}}
              "
                            class="bg-white text-black px-3 py-1 rounded hover:bg-gray-200">出品</a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                @if (!request()->routeIs(['register', 'verification.notice', 'login']))
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

        <!-- Mobile Menu Content -->
<div id="mobile-menu" class="hidden md:hidden bg-black text-white px-4 py-6 space-y-4">

    {{-- 検索フォーム --}}
    @if (!request()->routeIs('login') && !request()->routeIs(['register', 'verification.notice']))
        <form action="{{ route('items.index') }}" method="GET">
            @if (request('tab'))
                <input type="hidden" name="tab" value="{{ request('tab') }}">
            @endif
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                placeholder="なにをお探しですか？"
                class="w-full px-4 py-2 rounded text-black text-sm">
        </form>
    @endif

    {{-- 認証状態別リンク（ボタン風） --}}
    <div class="flex flex-col space-y-4">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="block w-full text-center bg-white text-black px-3 py-2 rounded hover:bg-gray-200">
                    ログアウト
                </button>
            </form>
            <a href="#" class="block w-full text-center bg-white text-black px-3 py-2 rounded hover:bg-gray-200">
                マイページ
            </a>
        @else
            <a href="#" class="block w-full text-center bg-white text-black px-3 py-2 rounded hover:bg-gray-200">
                ログイン
            </a>
            <a href="#" class="block w-full text-center bg-white text-black px-3 py-2 rounded hover:bg-gray-200">
                マイページ
            </a>
        @endauth
    </div>

    {{-- 出品ボタン（統一デザイン） --}}
    @if (!request()->routeIs(['register', 'verification.notice', 'login']))
        <a href="#"
            class="block w-full text-center bg-white text-black px-3 py-2 rounded hover:bg-gray-200">
            出品
        </a>
    @endif
</div>


        <script>
            document.getElementById('mobile-menu-button')?.addEventListener('click', () => {
                document.getElementById('mobile-menu')?.classList.toggle('hidden');
            });
        </script>
    </header>
    @yield('content')
</body>

</html>
