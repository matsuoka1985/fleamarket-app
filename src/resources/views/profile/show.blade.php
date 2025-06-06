@extends('layouts.app')

@section('content')
    <main class="bg-white py-6 px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 font-semibold text-center">
                {{ session('status') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto">
            <!-- ユーザープロフィール -->
            <div class="mb-8">
                <div class="max-w-3xl mx-auto flex flex-col sm:flex-row items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 overflow-hidden bg-gray-200 rounded-full">
                            <img src="{{ $user->image ? asset($user->image) : asset('images/default-user.png') }}"
                                alt="プロフィール画像" class="object-cover w-full h-full object-center">
                        </div>
                        <div class="text-xl font-bold text-gray-800">{{ $user->name }}</div>
                    </div>
                    <a href="{{ route('users.edit') }}"
                        class="mt-4 sm:mt-0 px-4 py-2 border border-red-400 text-red-500 rounded hover:bg-red-50 font-semibold text-sm">
                        プロフィールを編集
                    </a>
                </div>
            </div>

            <!-- タブメニュー -->
            <div class="flex border-b border-gray-300 mb-6 text-sm font-bold">
                <a href="{{ route('users.show', ['page' => 'sell']) }}">
                    <button id="tab-listed"
                        class="py-2 px-4 {{ $page === 'sell' ? 'text-red-500 border-b-2 border-red-500' : 'text-gray-500' }}">
                        出品した商品
                    </button>
                </a>
                <a href="{{ route('users.show', ['page' => 'buy']) }}">
                    <button id="tab-purchased"
                        class="py-2 px-4 {{ $page === 'buy' ? 'text-red-500 border-b-2 border-red-500' : 'text-gray-500' }}">
                        購入した商品
                    </button>
                </a>
            </div>

            <!-- 出品した商品 -->
            <div id="listed-items"
                class="{{ $page === 'sell' ? 'grid' : 'hidden' }} grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                @forelse ($listedItems as $item)
                    <div>
                        <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="block group">
                            <div
                                class="relative w-full aspect-square bg-gray-100 overflow-hidden rounded-md group-hover:shadow-md transition-shadow duration-200">
                                @php $image = optional($item->images->first())->image_url; @endphp
                                @if ($image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->title }}"
                                        class="object-cover w-full h-full transition-transform duration-200 group-hover:scale-105">
                                @else
                                    <span class="text-sm text-gray-500 flex items-center justify-center h-full">No
                                        image</span>
                                @endif
                            </div>
                            <div class="mt-2 text-sm text-gray-800 truncate text-left">
                                {{ $item->title }}
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-full">出品した商品はありません。</p>
                @endforelse
            </div>

            <!-- 購入した商品 -->
            <div id="purchased-items"
                class="{{ $page === 'buy' ? 'grid' : 'hidden' }} grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                @forelse ($purchasedItems as $item)
                    <div>
                        <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="block group">
                            <div
                                class="relative w-full aspect-square bg-gray-100 overflow-hidden rounded-md group-hover:shadow-md transition-shadow duration-200">
                                @php $image = optional($item->images->first())->image_url; @endphp
                                @if ($image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->title }}"
                                        class="object-cover w-full h-full transition-transform duration-200 group-hover:scale-105">
                                @else
                                    <span class="text-sm text-gray-500 flex items-center justify-center h-full">No
                                        image</span>
                                @endif
                            </div>
                            <div class="mt-2 text-sm text-gray-800 truncate text-left">
                                {{ $item->title }}
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-full">購入した商品はありません。</p>
                @endforelse
            </div>
        </div>
    </main>
@endsection
