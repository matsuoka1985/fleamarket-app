@extends('layouts.app')

@section('content')
    <main class="bg-white py-10 px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 font-semibold text-center">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 text-sm text-red-600 font-semibold text-center">
                {{ session('error') }}
            </div>
        @endif
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- 商品画像 -->
            <div class="relative bg-gray-100 aspect-square flex items-center justify-center overflow-hidden">
                @php $image = optional($item->images->first())->image_url; @endphp
                @if ($image)
                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->title }}"
                        class="object-cover w-full h-full {{ $item->status === 'sold' ? 'opacity-40' : '' }}">
                @else
                    <span class="text-sm text-gray-500">No image</span>
                @endif

                @if ($item->status === 'sold')
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <span class="text-white text-3xl font-bold">sold</span>
                    </div>
                @endif
            </div>

            <!-- 商品情報 -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $item->title }}</h1>
                <p class="text-sm text-gray-600 mb-2">{{ $item->brand_name ?? 'ブランド名' }}</p>
                <p class="text-2xl font-semibold text-gray-800 mb-4">
                    &yen;{{ number_format($item->price) }} <span class="text-sm">(税込)</span>
                </p>

                <div class="flex space-x-6 mb-6">
                    <!-- いいね -->
                    <div class="grid justify-items-center">
                        <div class="relative w-10 h-10 flex items-center justify-center">
                            @auth
                                <button type="button" id="like-button" data-url="{{ route('likes.toggle', $item->id) }}"
                                    class="w-full h-full flex items-center justify-center text-yellow-400 {{ $item->likes->contains('user_id', auth()->id()) ? 'text-3xl' : 'text-xl' }}">
                                    <span id="like-icon"
                                        class="transition-transform duration-200 ease-out">{{ $item->likes->contains('user_id', auth()->id()) ? '⭐' : '☆' }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="block w-full h-full flex items-center justify-center">
                                    <span class="text-gray-400 text-3xl">⭐</span>
                                </a>
                            @endauth
                        </div>
                        <span class="text-sm text-gray-800 mt-1" id="like-count">{{ $item->likes->count() }}</span>
                    </div>

                    <!-- コメント -->
                    <div class="grid justify-items-center">
                        <div class="w-10 h-10 flex items-center justify-center">
                            <span class="text-gray-500 text-3xl">💬</span>
                        </div>
                        <span class="text-sm text-gray-800 mt-1">{{ $item->comments->count() }}</span>
                    </div>
                </div>

                <div class="mb-6">

                    @auth
                        @if ($item->user_id !== auth()->id() && $item->status !== 'sold')
                            <form method="GET" action="{{ route('orders.create', ['item_id' => $item->id]) }}">
                                <button type="submit"
                                    class="w-full bg-red-500 text-white font-bold py-2 rounded hover:bg-red-600">
                                    購入手続きへ
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="block w-full text-center bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 rounded">
                            購入にはログインが必要です
                        </a>
                    @endauth
                </div>

                <!-- 商品説明 -->
                <section class="mb-6">
                    <h2 class="text-lg font-bold mb-2">商品説明</h2>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $item->description }}</p>
                </section>

                <!-- 商品の情報 -->
                <section class="mb-6">
                    <h2 class="text-lg font-bold mb-2">商品の情報</h2>
                    <div class="mt-2 mb-2">
                        <span class="font-semibold">ブランド名:</span>
                        <span class="ml-2">{{ $item->brand_name ?? '' }}</span>
                    </div>
                    <div class="mb-1">
                        <span class="font-semibold">カテゴリー:</span>
                        @foreach ($item->categories as $category)
                            <span class="inline-block bg-gray-200 text-sm text-gray-800 px-2 py-1 rounded mr-2">
                                {{ $category->label }}
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-2">
                        <span class="font-semibold">商品の状態:</span>
                        <span class="ml-2">{{ $item->condition ?? '良好' }}</span>
                    </div>
                </section>

                <!-- コメント欄 -->
                <section>
                    <h2 class="text-lg font-bold mb-2">コメント({{ $item->comments->count() }})</h2>
                    @foreach ($item->comments as $comment)
                        <div class="mb-4">
                            <div class="flex items-center space-x-2 mb-1">
                                @php
                                    $imagePath = public_path($comment->user->image ?? '');
                                    $hasImage = !empty($comment->user->image) && file_exists($imagePath);
                                @endphp

                                <div
                                    class="w-8 h-8 rounded-full overflow-hidden bg-gray-300 flex items-center justify-center">
                                    @if ($hasImage)
                                        <img src="{{ asset($comment->user->image) }}" alt="{{ $comment->user->name }}"
                                            class="w-full h-full object-cover object-center">
                                    @else
                                        <span class="text-white text-sm font-bold">
                                            {{ mb_substr($comment->user->name, 0, 1, 'UTF-8') }}
                                        </span>
                                    @endif
                                </div>




                                <p class="text-sm font-semibold">{{ $comment->user->name ?? '' }}</p>
                            </div>
                            <p class="text-sm text-gray-700 bg-gray-200 rounded px-4 py-2 inline-block">
                                {{ $comment->content }}
                            </p>
                        </div>
                    @endforeach

                    <form action="{{ route('comments.store', ['item_id' => $item->id]) }}" method="POST" class="mt-4">
                        @csrf
                        <label for="comment" class="block text-sm font-semibold mb-1">商品へのコメント</label>
                        {{-- <textarea id="comment" name="comment" rows="4" class="w-full border border-black rounded">{{ old('comment') }}</textarea> --}}
                        <textarea
                        id="comment"
                        name="comment"
                        class="h-40 w-full border border-black rounded px-3 py-2 text-base leading-normal focus:outline-none focus:ring-2 focus:ring-black-400"
                    >{{ old('comment') }}</textarea>


                        @error('comment')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="mt-2 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            コメントを送信する
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </main>
@endsection

@auth
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const likeButton = document.getElementById('like-button');
                if (!likeButton) return;

                likeButton.addEventListener('click', async () => {
                    const url = likeButton.dataset.url;

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                        });

                        if (!res.ok) return;

                        const data = await res.json();
                        const icon = document.getElementById('like-icon');
                        const count = document.getElementById('like-count');

                        icon.textContent = data.liked ? '⭐' : '☆';
                        likeButton.classList.toggle('text-3xl', data.liked);
                        likeButton.classList.toggle('text-xl', !data.liked);
                        count.textContent = data.count;

                        // アニメーション効果
                        if (data.liked) {

                            icon.classList.add('scale-125');
                            setTimeout(() => {
                                icon.classList.remove('scale-125');
                            }, 150);
                        }

                    } catch (e) {
                        console.error('Like toggle failed', e);
                    }
                });
            });
        </script>
    @endpush
@endauth
