@extends('layouts.app')

@section('content')
    <main class="bg-white py-6 px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 font-semibold text-center">
                {{ session('status') }}
            </div>
        @endif
        <div class="max-w-7xl mx-auto">
            <!-- タブメニュー -->
            <div class="flex border-b border-gray-300 mb-6 text-sm font-bold">
                <a href="{{ route('items.index') }}"
                    class="py-2 px-4 {{ request('tab') !== 'mylist' ? 'text-red-500 border-b-2 border-red-500' : 'text-gray-500' }}">
                    おすすめ
                </a>
                <a href="{{ auth()->check() ? route('items.index', ['tab' => 'mylist']) : route('login') }}"
                    class="py-2 px-4 {{ request('tab') === 'mylist' ? 'text-red-500 border-b-2 border-red-500' : 'text-gray-500' }}">
                    マイリスト
                </a>
            </div>

            <!-- 商品一覧グリッド -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                @foreach ($items as $item)
                    <div>
                        <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="block group">
                            <div
                                class="relative w-full aspect-square bg-gray-100 overflow-hidden rounded-md group-hover:shadow-md transition-shadow duration-200">
                                @php
                                    $image = optional($item->images->first())->image_url;
                                    $isSold = $item->status === 'sold';
                                @endphp

                                @if ($image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->title }}"
                                        class="object-cover w-full h-full transition-transform duration-200 group-hover:scale-105 {{ $isSold ? 'opacity-40' : '' }}">
                                @else
                                    <span class="text-sm text-gray-500 flex items-center justify-center h-full">No
                                        image</span>
                                @endif

                                @if ($isSold)
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span
                                            class="bg-black bg-opacity-75 text-white text-lg font-bold px-4 py-1 rounded">sold</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2 text-sm text-gray-800 truncate text-left">
                                {{ $item->title }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
