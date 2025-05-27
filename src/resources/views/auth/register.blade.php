@extends('layouts.app')

@section('content')


<main class="min-h-screen bg-white flex items-start justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="w-full max-w-md space-y-8">
    <!-- タイトル -->
    <h2 class="text-center text-2xl font-bold text-gray-900">会員登録</h2>

    <!-- フォーム -->
    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
      @csrf
      <div class="space-y-4">
        <!-- ユーザー名 -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">ユーザー名</label>
          <input id="name" name="name" type="text" required
            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>
        <!-- メールアドレス -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
          <input id="email" name="email" type="email" required
            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>
        <!-- パスワード -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">パスワード</label>
          <input id="password" name="password" type="password" required
            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>
        <!-- 確認用パスワード -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">確認用パスワード</label>
          <input id="password_confirmation" name="password_confirmation" type="password" required
            class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-400">
        </div>
      </div>
      <!-- 登録ボタン -->
      <div>
        <button type="submit"
          class="w-full flex justify-center py-2 px-4 bg-red-400 hover:bg-red-500 text-white font-semibold rounded">
          登録する
        </button>
      </div>
    </form>

    <!-- ログインリンク -->
    <p class="mt-4 text-center">
      <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:underline">
        ログインはこちら
      </a>
    </p>
  </div>
</main>
@endsection
