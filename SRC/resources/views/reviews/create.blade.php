<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('books.show', $book) }}" class="flex items-center space-x-2 text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
                <div class="h-6 w-px bg-gray-300"></div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Viết đánh giá cho "{{ Str::limit($book->title, 50) }}"
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Book Info Header -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-8 border-b border-gray-200">
                    <div class="flex items-center space-x-6">
                        <img src="{{ $book->cover_url }}" 
                             alt="{{ $book->title }}" 
                             class="w-24 h-32 object-cover rounded-lg shadow-lg">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $book->title }}</h1>
                            <p class="text-lg text-gray-600 mb-2">bởi <span class="font-semibold">{{ $book->author }}</span></p>
                            @if($hasBorrowed)
                                <div class="flex items-center space-x-2 text-green-600">
                                    <i class="fas fa-check-circle"></i>
                                    <span class="text-sm font-medium">Bạn đã mượn cuốn sách này - Đánh giá được xác thực</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Review Form -->
                <div class="p-8">
                    <form action="{{ route('reviews.store', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <!-- Rating Section -->
                        <div>
                            <label class="block text-lg font-semibold text-gray-800 mb-4">
                                Đánh giá của bạn <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2" id="rating-container">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="rating-star text-4xl text-gray-300 hover:text-yellow-400 transition-colors duration-200" 
                                            data-rating="{{ $i }}">
                                        <i class="far fa-star"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="0" required>
                            <p class="text-sm text-gray-500 mt-2">Nhấp vào sao để chọn điểm đánh giá</p>
                            @error('rating')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Review Text -->
                        <div>
                            <label for="review_text" class="block text-lg font-semibold text-gray-800 mb-4">
                                Nhận xét chi tiết
                            </label>
                            <textarea name="review_text" 
                                      id="review_text" 
                                      rows="6" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 resize-none"
                                      placeholder="Chia sẻ suy nghĩ của bạn về cuốn sách này... (tùy chọn)">{{ old('review_text') }}</textarea>
                            <p class="text-sm text-gray-500 mt-2">Tối đa 2000 ký tự</p>
                            @error('review_text')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label for="images" class="block text-lg font-semibold text-gray-800 mb-4">
                                Hình ảnh minh họa
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-400 transition-colors duration-300">
                                <input type="file" 
                                       name="images[]" 
                                       id="images" 
                                       multiple 
                                       accept="image/*" 
                                       class="hidden">
                                <label for="images" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-600 mb-2">Nhấp để tải lên hình ảnh</p>
                                    <p class="text-sm text-gray-500">PNG, JPG, GIF tối đa 2MB mỗi ảnh</p>
                                </label>
                            </div>
                            <div id="image-preview" class="grid grid-cols-4 gap-4 mt-4 hidden">
                                <!-- Preview images will be inserted here -->
                            </div>
                            @error('images.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Guidelines -->
                        <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                            <h3 class="font-semibold text-blue-800 mb-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                Hướng dẫn viết đánh giá
                            </h3>
                            <ul class="text-sm text-blue-700 space-y-2">
                                <li>• Chia sẻ trải nghiệm thực tế của bạn khi đọc cuốn sách</li>
                                <li>• Tránh tiết lộ nội dung quan trọng (spoiler)</li>
                                <li>• Sử dụng ngôn ngữ lịch sự và xây dựng</li>
                                <li>• Đánh giá dựa trên nội dung, không phải tác giả</li>
                            </ul>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('books.show', $book) }}" 
                               class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-300">
                                Hủy
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Gửi đánh giá
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
            const ratingStars = document.querySelectorAll('.rating-star');
            const ratingInput = document.getElementById('rating-input');
            const imageInput = document.getElementById('images');
            const imagePreview = document.getElementById('image-preview');

            // Rating functionality
            ratingStars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    ratingInput.value = rating;
                    
                    // Update star display
                    ratingStars.forEach((s, i) => {
                        const icon = s.querySelector('i');
                        if (i < rating) {
                            icon.className = 'fas fa-star text-yellow-400';
                        } else {
                            icon.className = 'far fa-star text-gray-300';
                        }
                    });
                });

                star.addEventListener('mouseenter', function() {
                    const rating = parseInt(this.dataset.rating);
                    ratingStars.forEach((s, i) => {
                        const icon = s.querySelector('i');
                        if (i < rating) {
                            icon.className = 'fas fa-star text-yellow-400';
                        } else {
                            icon.className = 'far fa-star text-gray-300';
                        }
                    });
                });
            });

            // Reset stars on mouse leave
            document.getElementById('rating-container').addEventListener('mouseleave', function() {
                const currentRating = parseInt(ratingInput.value);
                ratingStars.forEach((s, i) => {
                    const icon = s.querySelector('i');
                    if (i < currentRating) {
                        icon.className = 'fas fa-star text-yellow-400';
                    } else {
                        icon.className = 'far fa-star text-gray-300';
                    }
                });
            });

            // Image preview functionality
            imageInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                imagePreview.innerHTML = '';
                
                if (files.length > 0) {
                    imagePreview.classList.remove('hidden');
                    
                    files.forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const div = document.createElement('div');
                                div.className = 'relative group';
                                div.innerHTML = `
                                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                                    <button type="button" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200" onclick="removeImage(${index})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                `;
                                imagePreview.appendChild(div);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                } else {
                    imagePreview.classList.add('hidden');
                }
            });

            // Remove image function
            window.removeImage = function(index) {
                const dt = new DataTransfer();
                const files = Array.from(imageInput.files);
                files.splice(index, 1);
                
                files.forEach(file => dt.items.add(file));
                imageInput.files = dt.files;
                
                // Re-trigger change event
                imageInput.dispatchEvent(new Event('change'));
            };
        });
    </script>
    @endpush
</x-app-layout>
