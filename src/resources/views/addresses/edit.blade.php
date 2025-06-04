@extends('layouts.app')

@section('content')
    <main class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-white py-12">
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 font-semibold text-center">
                {{ session('status') }}
            </div>
        @endif
        <div class="w-full max-w-md">
            <!-- タイトル -->
            <h2 class="text-center text-2xl font-bold text-gray-900 mb-10">住所の変更</h2>

            <!-- フォーム -->
            <form method="POST" action="{{ route('orders.updateAddress') }}">
                @csrf
                @method('POST')

                <!-- 郵便番号 -->
                <div class="mb-6">
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">郵便番号</label>
                    <input id="postal_code" name="postal_code" type="text"
                        value="{{ old('postal_code', optional($address)->postal_code) }}"
                        class="block w-full px-3 py-2 rounded border focus:outline-none focus:ring-2 focus:ring-red-400
                       @error('postal_code') border-red-500 @else border-gray-300 @enderror">
                    @error('postal_code')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 住所 -->
                <div class="mb-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">住所</label>
                    <input id="address" name="address" type="text"
                        value="{{ old('address', optional($address)->address) }}"
                        class="block w-full px-3 py-2 rounded border focus:outline-none focus:ring-2 focus:ring-red-400
                       @error('address') border-red-500 @else border-gray-300 @enderror">
                    @error('address')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 建物名 -->
                <div class="mb-8">
                    <label for="building" class="block text-sm font-medium text-gray-700 mb-1">建物名</label>
                    <input id="building" name="building" type="text"
                        value="{{ old('building', optional($address)->building) }}"
                        class="block w-full px-3 py-2 rounded border focus:outline-none focus:ring-2 focus:ring-red-400
                       @error('building') border-red-500 @else border-gray-300 @enderror">
                    @error('building')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 更新ボタン -->
                <div>
                    <button type="submit"
                        class="w-full bg-red-400 hover:bg-red-500 text-white font-semibold py-2 px-4 rounded">
                        更新する
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
