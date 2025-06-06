@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12">
        <div class="w-full max-w-md text-center space-y-6">
            <h2 class="text-2xl font-bold text-gray-800">ご購入ありがとうございます！</h2>
            <p class="text-gray-700">
                ご注文が正常に完了しました。<br>
                お支払いが確認され次第、出品者によって発送されます。
            </p>
            <a href="{{ route('items.index') }}"
                class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded">
                トップに戻る
            </a>
        </div>
    </div>
@endsection
