@extends('layouts.app')

@section('content')
    <main class="min-h-screen bg-white flex items-start justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <!-- タイトル -->
            <h2 class="text-center text-2xl font-bold text-gray-900">プロフィール設定</h2>

            @if (session('status'))
                <div class="mb-4 text-sm text-green-600 font-semibold text-center">
                    {{ session('status') }}
                </div>
            @endif

            <!-- フォーム -->
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- プロフィール画像 -->
                <div class="flex flex-col items-center space-y-4">
                    {{-- プロフィール画像（nullならデフォルト画像） --}}
                    <img id="currentProfileImg" src="{{ $user->image ? asset($user->image) : asset('images/default-user.png') }}"
                        alt="プロフィール画像" class="w-40 h-40 object-cover ">

                    <!-- ファイル選択ボタン -->
                    <label class="cursor-pointer px-3 py-1 border border-red-500 text-red-500 text-sm rounded font-semibold">
                        画像を選択する
                        <input type="file" name="image" id="imageInput" class="hidden" accept="image/*">
                    </label>

                    <!-- プレビュー表示 -->
                    <div id="imagePreview" class="mt-2 space-y-2 hidden">
                        <p class="text-sm text-gray-700" id="fileName"></p>
                        <img id="previewImg" class="mx-auto max-h-40 rounded shadow">
                    </div>
                </div>

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
                        {{ $address ? '更新する' : '登録する' }}
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('imageInput');
        const previewContainer = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const fileNameText = document.getElementById('fileName');

        input?.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            fileNameText.textContent = file.name;

            const reader = new FileReader();
            reader.onload = (event) => {
                previewImg.src = event.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
