@if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Có lỗi xảy ra!</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Tiêu đề -->
<div class="mb-4">
    <label for="title" class="block text-sm font-medium text-gray-700">Tiêu đề</label>
    <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('title', $book->title ?? '') }}" required>
</div>

<!-- Tác giả -->
<div class="mb-4">
    <label for="author" class="block text-sm font-medium text-gray-700">Tác giả</label>
    <input type="text" name="author" id="author" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('author', $book->author ?? '') }}" required>
</div>

<!-- Mô tả -->
<div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
    <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $book->description ?? '') }}</textarea>
</div>

<!-- Ảnh bìa -->
<div class="mb-4">
    <label for="cover_image" class="block text-sm font-medium text-gray-700">Ảnh bìa</label>
    <input type="file" name="cover_image" id="cover_image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
    @if(isset($book) && $book->cover_image_path)
        <div class="mt-2">
            <img src="{{ $book->cover_url }}" alt="Ảnh bìa" class="h-40 w-auto rounded">
        </div>
    @endif
</div>

<!-- Loại tài liệu (Physical/Online) -->
<div class="mb-4">
    <label for="type" class="block text-sm font-medium text-gray-700">Loại tài liệu</p>
    <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        <option value="physical" {{ old('type', $book->type ?? '') == 'physical' ? 'selected' : '' }}>Sách vật lý (Physical)</option>
        <option value="online" {{ old('type', $book->type ?? '') == 'online' ? 'selected' : '' }}>Tài liệu online</option>
    </select>
</div>

<!-- Các trường phụ thuộc vào Type -->
<div id="type-fields">
    <!-- Trường Số lượng (cho Physical) -->
    <div id="quantity-field" class="mb-4 {{ old('type', $book->type ?? 'physical') == 'physical' ? '' : 'hidden' }}">
        <label for="quantity" class="block text-sm font-medium text-gray-700">Số lượng</label>
        <input type="number" name="quantity" id="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('quantity', $book->quantity ?? 1) }}" min="0">
    </div>
    
    <!-- Trường Nội dung (cho Online) -->
<div id="content-field" class="mb-4 {{ old('type', $book->type ?? '') == 'online' ? '' : 'hidden' }}">
    <x-input-label for="document_file" :value="__('Tải lên file tài liệu (.txt)')" />
    <input type="file" name="document_file" id="document_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".txt">
    
    @if(isset($book) && $book->content)
        <p class="mt-2 text-sm text-gray-600">
            <i class="fa-solid fa-file-lines mr-1"></i> 
            Tài liệu hiện tại đã có nội dung. Tải file mới sẽ ghi đè lên nội dung cũ.
        </p>
    @endif
    
    <x-input-error :messages="$errors->get('document_file')" class="mt-2" />
    <x-input-error :messages="$errors->get('content')" class="mt-2" />
</div>
</div>

<!-- Thêm vào cuối form, trước nút Lưu -->
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Danh mục</label>
    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($categories as $category)
            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                        @if(isset($book) && $book->categories->contains($category->id)) checked @endif
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">{{ $category->name }}</span>
                </label>
            </div>
        @endforeach
    </div>
</div>

<!-- Nút Submit -->
<div class="flex items-center justify-end mt-4">
    <a href="{{ route('admin.books.index') }}" class="mr-4 text-gray-600">Hủy</a>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        Lưu
    </button>
</div>

<!-- JavaScript để ẩn/hiện trường theo Type -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const quantityField = document.getElementById('quantity-field');
        const contentField = document.getElementById('content-field');
        const quantityInput = document.getElementById('quantity');
        const contentInput = document.getElementById('content');

        function toggleFields() {
            if (typeSelect.value === 'physical') {
                quantityField.classList.remove('hidden');
                contentField.classList.add('hidden');
                contentInput.value = ''; // Xóa nội dung khi chuyển sang physical
            } else {
                quantityField.classList.add('hidden');
                contentField.classList.remove('hidden');
                quantityInput.value = 0; // Set số lượng về 0 khi chuyển sang online
            }
        }

        typeSelect.addEventListener('change', toggleFields);
    });
</script>