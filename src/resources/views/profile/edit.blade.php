@extends('layouts.app')

@section('content')
    <main class="min-h-screen bg-white flex items-start justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <!-- タイトル -->
            <h2 class="text-center text-2xl font-bold text-gray-900">プロフィール設定</h2>

            <!-- フォーム -->
            <form method="POST"
                action="{{ route('profile.update') }}"
                enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- プロフィール画像 -->
                <div class="flex items-center space-x-4">
                    {{-- 丸型プロフィール画像（nullならデフォ画像） --}}
                    <img src="{{ $user->image ? asset($user->image) : asset('images/default-user.png') }}" alt="プロフィール画像"
                        class="w-56 h-56 object-cover rounded-full">

                    {{-- ファイル選択ボタン --}}
                    <label
                        class="cursor-pointer px-3 py-1 border border-red-500 text-red-500 text-sm rounded font-semibold">
                        画像を選択する
                        <input type="file" name="image" class="hidden">
                    </label>
                </div>

                {{-- エラーメッセージ --}}
                @error('image')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                <div class="space-y-4">
                    <!-- ユーザー名 -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">ユーザー名</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                            class="mt-1 block w-full px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-400
                            border @error('name') border-red-500 @else border-gray-300 @enderror">
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 郵便番号 -->
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">郵便番号</label>
                        <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code', optional($address)->postal_code) }}"
                            class="mt-1 block w-full px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-400
                            border @error('postal_code') border-red-500 @else border-gray-300 @enderror">
                        @error('postal_code')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 住所 -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">住所</label>
                        <input id="address" name="address" type="text" value="{{ old('address', optional($address)->address) }}"
                            class="mt-1 block w-full px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-400
                            border @error('address') border-red-500 @else border-gray-300 @enderror">
                        @error('address')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 建物名 -->
                    <div>
                        <label for="building" class="block text-sm font-medium text-gray-700">建物名</label>
                        <input id="building" name="building" type="text" value="{{ old('building', optional($address)->building) }}"
                            class="mt-1 block w-full px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-red-400
                            border @error('building') border-red-500 @else border-gray-300 @enderror">
                        @error('building')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 更新ボタン -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 bg-red-400 hover:bg-red-500 text-white font-semibold rounded">
                        更新する
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
