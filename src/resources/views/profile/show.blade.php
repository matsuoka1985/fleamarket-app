@extends('layouts.app')

@section('content')
<main class="bg-white py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- ユーザープロフィール -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8">
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden bg-gray-200">
                    <img src="{{ $user->image ? asset($user->image) : asset('images/default-user.png') }}"
                         alt="プロフィール画像" class="object-cover w-full h-full">
                </div>
                <div class="text-xl font-bold text-gray-800">{{ $user->name }}</div>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="mt-4 sm:mt-0 px-4 py-2 border border-red-400 text-red-500 rounded hover:bg-red-50 font-semibold text-sm">
                プロフィールを編集
            </a>
        </div>

        <!-- タブメニュー -->
        <div class="flex border-b border-gray-300 mb-6 text-sm font-bold">
            <button id="tab-listed"
                    class="py-2 px-4 text-red-500 border-b-2 border-red-500 focus:outline-none">
                出品した商品
            </button>
            <button id="tab-purchased"
                    class="py-2 px-4 text-gray-500 focus:outline-none">
                購入した商品
            </button>
        </div>

        <!-- 出品した商品 -->
        <div id="listed-items" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @forelse ($listedItems as $item)
                <div>
                    <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="block group">
                        <div class="relative w-full aspect-square bg-gray-100 overflow-hidden rounded-md group-hover:shadow-md transition-shadow duration-200">
                            @php
                                $image = optional($item->images->first())->image_url;
                                $isSold = $item->status === 'sold';
                            @endphp

                            @if ($image)
                                <img src="{{ asset('storage/' . $image) }}"
                                     alt="{{ $item->title }}"
                                     class="object-cover w-full h-full transition-transform duration-200 group-hover:scale-105 {{ $isSold ? 'opacity-40' : '' }}">
                            @else
                                <span class="text-sm text-gray-500 flex items-center justify-center h-full">No image</span>
                            @endif

                            @if ($isSold)
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="bg-black bg-opacity-75 text-white text-lg font-bold px-4 py-1 rounded">SOLD</span>
                                </div>
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

        <!-- 購入した商品（非表示、JSで切替） -->
        <div id="purchased-items" class="hidden grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @forelse ($purchasedItems as $item)
                <div>
                    <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="block group">
                        <div class="relative w-full aspect-square bg-gray-100 overflow-hidden rounded-md group-hover:shadow-md transition-shadow duration-200">
                            @php
                                $image = optional($item->images->first())->image_url;
                            @endphp

                            @if ($image)
                                <img src="{{ asset('storage/' . $image) }}"
                                     alt="{{ $item->title }}"
                                     class="object-cover w-full h-full transition-transform duration-200 group-hover:scale-105">
                            @else
                                <span class="text-sm text-gray-500 flex items-center justify-center h-full">No image</span>
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const listedTab = document.getElementById('tab-listed');
        const purchasedTab = document.getElementById('tab-purchased');
        const listedItems = document.getElementById('listed-items');
        const purchasedItems = document.getElementById('purchased-items');

        listedTab.addEventListener('click', () => {
            listedTab.classList.add('text-red-500', 'border-b-2', 'border-red-500');
            purchasedTab.classList.remove('text-red-500', 'border-b-2', 'border-red-500');
            listedTab.classList.add('text-red-500');
            purchasedTab.classList.add('text-gray-500');
            listedItems.classList.remove('hidden');
            purchasedItems.classList.add('hidden');
        });

        purchasedTab.addEventListener('click', () => {
            purchasedTab.classList.add('text-red-500', 'border-b-2', 'border-red-500');
            listedTab.classList.remove('text-red-500', 'border-b-2', 'border-red-500');
            purchasedTab.classList.add('text-red-500');
            listedTab.classList.add('text-gray-500');
            purchasedItems.classList.remove('hidden');
            listedItems.classList.add('hidden');
        });
    });
</script>
@endsection
