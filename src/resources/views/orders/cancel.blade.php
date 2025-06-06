@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12">
  <div class="w-full max-w-md text-center space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">決済がキャンセルされました</h2>

    <p class="text-gray-700">
      商品「<strong>{{ $item->title }}</strong>」の購入はキャンセルされました。
    </p>

    <p class="text-sm text-gray-500">
      再度購入される場合は、商品ページに戻って手続きをやり直してください。
    </p>

    <a href="{{ route('items.show', ['item' => $item->id]) }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded">
      商品ページへ戻る
    </a>
  </div>
</div>
@endsection

