<!-- resources/views/items/index.blade.php -->
@extends('layouts.app')

@section('content')
<main class="bg-white py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- タブメニュー -->
        <div class="flex border-b border-gray-300 mb-6 text-sm font-bold">
            <a href="{{ route('items.index') }}" class="py-2 px-4 {{ request('tab') !== 'mylist' ? 'text-red-500 border-b-2 border-red-500' : 'text-gray-500' }}">
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
                <div class="text-center">
                    <div class="w-full aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
                        @php
                            $image = optional($item->images->first())->image_url;
                        @endphp
                        @if ($image)
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->title }}" class="object-cover w-full h-full">
                        @else
                            <span class="text-sm text-gray-500">No image</span>
                        @endif
                    </div>
                    <div class="mt-2 text-sm text-gray-800 truncate">{{ $item->title }}</div>
                </div>
            @endforeach
        </div>
    </div>
</main>
@endsection
