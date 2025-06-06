@extends('layouts.app')

@section('content')
    <main class="min-h-screen bg-white flex items-start justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl space-y-8">
            <!-- タイトル -->
            <h2 class="text-center text-2xl font-bold text-gray-900">商品の出品</h2>

            <!-- フォーム -->
            <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- 商品画像 -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">商品画像</label>
                    <div class="border-2 border-dashed border-gray-300 rounded p-8 text-center">
                        <label
                            class="inline-block border border-red-500 text-red-500 text-sm px-4 py-2 rounded cursor-pointer">
                            画像を選択する
                            <input type="file" name="image" id="imageInput" class="hidden" accept="image/*">
                        </label>

                        <!-- 画像ファイル名とプレビュー -->
                        <div id="imagePreview" class="mt-4 space-y-2 hidden">
                            <p class="text-sm text-gray-700" id="fileName"></p>
                            <img id="previewImg" class="mx-auto max-h-48 rounded shadow">
                        </div>
                    </div>
                    @error('image')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 商品詳細 -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900">商品の詳細</h3>
                    <hr>

                    <!-- カテゴリー -->
                    <div>
                        <p class="block text-sm font-bold text-gray-700 mb-2">カテゴリー</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($categories as $category)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                        class="peer hidden"
                                        {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                                    <span
                                        class="border border-red-400 text-red-500 px-3 py-1 rounded-full text-sm peer-checked:bg-red-500 peer-checked:text-white">
                                        {{ $category->label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('category_ids')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- 商品の状態 -->
                    <div>
                        <label for="condition" class="block text-sm font-bold text-gray-700 mb-1">商品の状態</label>
                        <select id="condition" name="condition"
                            class="w-full border border-black rounded px-3 py-2 text-sm">
                            <option value="">選択してください</option>
                            <option value="良好">良好</option>
                            <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                            <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                            <option value="状態が悪い">状態が悪い</option>
                        </select>
                        @error('condition')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 商品名と説明 -->
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-bold text-gray-700">商品名</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                class="w-full border border-black rounded px-3 py-2">
                            @error('title')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="brand_name" class="block text-sm font-bold text-gray-700">ブランド名</label>
                            <input type="text" id="brand_name" name="brand_name" value="{{ old('brand_name') }}"
                                class="w-full border border-black rounded px-3 py-2">
                            @error('brand_name')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700">商品の説明</label>
                            <textarea id="description" name="description" rows="4" class="w-full border border-black rounded px-3 py-2">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 販売価格 -->
                    <div>
                        <label for="price" class="block text-sm font-bold text-gray-700">販売価格</label>
                        <input type="text" id="price" name="price" value="{{ old('price') }}" placeholder="¥0"
                            inputmode="numeric" class="w-full border border-black rounded px-3 py-2">
                        @error('price')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 出品ボタン -->
                <div>
                    <button type="submit"
                        class="w-full py-2 px-4 bg-red-400 hover:bg-red-500 text-white font-semibold rounded">
                        出品する
                    </button>
                </div>
            </form>
        </div>
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const input = document.getElementById('imageInput');
                    const previewContainer = document.getElementById('imagePreview');
                    const previewImg = document.getElementById('previewImg');
                    const fileNameText = document.getElementById('fileName');

                    input.addEventListener('change', (e) => {
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
    </main>
@endsection
