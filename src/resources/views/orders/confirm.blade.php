@extends('layouts.app')

@section('content')
    <main class="bg-white py-10 px-4 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 text-sm text-green-600 font-semibold text-center">
                {{ session('status') }}
            </div>
        @endif
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-10">
            <!-- 左カラム -->
            <div class="space-y-10">
                <!-- 商品情報 -->
                <div class="flex items-start space-x-4">
                    <div class="w-32 h-32 bg-gray-200 flex items-center justify-center text-sm text-gray-500">
                        @php
                            $image = optional($item->images->first())->image_url;
                        @endphp
                        @if ($image)
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->title }}"
                                class="object-cover w-32 h-32 rounded">
                        @else
                            商品画像
                        @endif
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">{{ $item->title }}</h2>
                        <p class="text-xl font-semibold text-gray-900 mt-1">&yen;{{ number_format($item->price) }}</p>
                    </div>
                </div>

                <!-- 支払い方法 -->
                <div class="border-t border-gray-400 pt-6">
                    <h3 class="text-sm font-bold mb-2 text-gray-800">支払い方法</h3>
                    <div class="pl-6">
                        <select name="payment_method" id="payment-method"
                            class="border border-gray-300 rounded px-3 py-2 text-sm w-full max-w-sm">
                            <option value="">選択してください</option>
                            <option value="konbini">コンビニ払い</option>
                            <option value="card">クレジットカード</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 配送先 -->
                <div class="border-t border-b border-gray-400 pt-6 pb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-bold text-gray-800">配送先</h3>
                        <a href="{{ route('orders.editAddress', ['item_id' => $item->id]) }}"
                            class="text-sm text-blue-600 hover:underline">
                            変更する
                        </a>
                    </div>
                    <div class="pl-6 text-sm text-gray-800 leading-relaxed">
                        @if ($address)
                            <p>〒 {{ $address->postal_code }}</p>
                            <p>{{ $address->address }}{{ $address->building }}</p>
                        @else
                            <p class="text-red-500">住所情報が登録されていません。</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 右カラム -->
            <div class="space-y-6 h-fit">
                <!-- 明細ボックス -->
                <div class="border border-gray-400">
                    <!-- 商品代金 -->
                    <div class="flex justify-between items-center px-4 py-10 border-b border-gray-400 text-sm">
                        <span class="text-gray-800">商品代金</span>
                        <span class="text-gray-900 font-semibold">&yen;{{ number_format($item->price) }}</span>
                    </div>
                    <!-- 支払い方法 -->
                    <div class="flex justify-between items-center px-4 py-10 text-sm">
                        <span class="text-gray-800">支払い方法</span>
                        <span class="text-gray-900" id="selected-payment-method">未選択</span>
                    </div>
                </div>

                <!-- 購入ボタン -->
                <form method="POST" action="{{ route('orders.checkout', $item->id) }}" id="payment-form">
                    @csrf
                    <input type="hidden" name="payment_method" id="payment-method-hidden" value="">

                    <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded mt-6">
                        購入する
                    </button>
                </form>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('payment-method');
            const output = document.getElementById('selected-payment-method');
            const hidden = document.getElementById('payment-method-hidden');

            select.addEventListener('change', function() {
                const value = select.value;
                output.textContent = value === 'card' ? 'クレジットカード' : value === 'konbini' ? 'コンビニ払い' :
                    '未選択';
                hidden.value = value;
            });
        });
    </script>
@endsection
