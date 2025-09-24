<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('discussions.index') }}" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
                <div class="h-6 w-px bg-gray-300"></div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">
                    Tạo thảo luận mới
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-green-50 to-teal-50 p-8 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white text-2xl">
                            <i class="fas fa-comment-plus"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Tạo thảo luận mới</h1>
                            <p class="text-gray-600">Chia sẻ suy nghĩ và thảo luận với cộng đồng</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-8">
                    <form action="{{ route('discussions.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <!-- Discussion Type -->
                        <div>
                            <label class="block text-lg font-semibold text-gray-800 mb-4">
                                Loại thảo luận <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="type" value="general" class="sr-only" checked>
                                    <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-green-300 transition-all duration-200 radio-option">
                                        <div class="text-center">
                                            <i class="fas fa-comments text-3xl text-gray-400 mb-3"></i>
                                            <h3 class="font-semibold text-gray-800">Chung</h3>
                                            <p class="text-sm text-gray-600">Thảo luận chung về sách và đọc sách</p>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="type" value="book_discussion" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-green-300 transition-all duration-200 radio-option">
                                        <div class="text-center">
                                            <i class="fas fa-book text-3xl text-gray-400 mb-3"></i>
                                            <h3 class="font-semibold text-gray-800">Thảo luận sách</h3>
                                            <p class="text-sm text-gray-600">Thảo luận về một cuốn sách cụ thể</p>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="type" value="reading_group" class="sr-only">
                                    <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-green-300 transition-all duration-200 radio-option">
                                        <div class="text-center">
                                            <i class="fas fa-users text-3xl text-gray-400 mb-3"></i>
                                            <h3 class="font-semibold text-gray-800">Nhóm đọc</h3>
                                            <p class="text-sm text-gray-600">Thảo luận trong nhóm đọc</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('type')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Book Selection (for book_discussion) -->
                        <div id="book-selection" class="hidden">
                            <label for="book_id" class="block text-lg font-semibold text-gray-800 mb-4">
                                Chọn sách để thảo luận
                            </label>
                            <select name="book_id" id="book_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300">
                                <option value="">Chọn sách...</option>
                                @foreach(\App\Models\Book::orderBy('title')->get() as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }} - {{ $book->author }}</option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-lg font-semibold text-gray-800 mb-4">
                                Tiêu đề thảo luận <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title') }}"
                                   placeholder="Nhập tiêu đề thảo luận..."
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300"
                                   required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-lg font-semibold text-gray-800 mb-4">
                                Nội dung thảo luận <span class="text-red-500">*</span>
                            </label>
                            <textarea name="content" 
                                      id="content" 
                                      rows="10" 
                                      placeholder="Chia sẻ suy nghĩ của bạn..."
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 resize-none"
                                      required>{{ old('content') }}</textarea>
                            <p class="text-sm text-gray-500 mt-2">Tối đa 5000 ký tự</p>
                            @error('content')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div>
                            <label for="tags" class="block text-lg font-semibold text-gray-800 mb-4">
                                Tags (tùy chọn)
                            </label>
                            <input type="text" 
                                   name="tags" 
                                   id="tags" 
                                   value="{{ old('tags') }}"
                                   placeholder="Ví dụ: sách hay, review, tiểu thuyết, phiêu lưu..."
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300">
                            <p class="text-sm text-gray-500 mt-2">Nhập tags cách nhau bởi dấu phẩy (tối đa 5 tags)</p>
                            @error('tags')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Guidelines -->
                        <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                            <h3 class="font-semibold text-blue-800 mb-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                Hướng dẫn tạo thảo luận
                            </h3>
                            <ul class="text-sm text-blue-700 space-y-2">
                                <li>• Sử dụng tiêu đề rõ ràng và mô tả nội dung thảo luận</li>
                                <li>• Chia sẻ suy nghĩ chân thành và xây dựng</li>
                                <li>• Sử dụng tags phù hợp để dễ tìm kiếm</li>
                                <li>• Tôn trọng ý kiến của người khác</li>
                                <li>• Tránh spam và nội dung không phù hợp</li>
                            </ul>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('discussions.index') }}" 
                               class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-300">
                                Hủy
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 bg-gradient-to-r from-green-500 to-teal-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Tạo thảo luận
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeRadios = document.querySelectorAll('input[name="type"]');
            const bookSelection = document.getElementById('book-selection');
            const bookIdSelect = document.getElementById('book_id');

            // Handle type selection
            typeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Update visual selection
                    document.querySelectorAll('.radio-option').forEach(option => {
                        option.classList.remove('border-green-500', 'bg-green-50');
                        option.classList.add('border-gray-200');
                    });
                    
                    if (this.checked) {
                        this.closest('label').querySelector('.radio-option').classList.add('border-green-500', 'bg-green-50');
                    }

                    // Show/hide book selection
                    if (this.value === 'book_discussion') {
                        bookSelection.classList.remove('hidden');
                        bookIdSelect.required = true;
                    } else {
                        bookSelection.classList.add('hidden');
                        bookIdSelect.required = false;
                        bookIdSelect.value = '';
                    }
                });
            });

            // Initialize first option as selected
            if (typeRadios[0]) {
                typeRadios[0].checked = true;
                typeRadios[0].closest('label').querySelector('.radio-option').classList.add('border-green-500', 'bg-green-50');
            }
        });
    </script>
    @endpush
</x-app-layout>
