<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-white text-lg"></i>
                </div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    {{ __('Thư Viện Sách BookHaven') }}
                </h2>
            </div>
            <div class="hidden md:flex items-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center space-x-1">
                    <i class="fas fa-book-open text-indigo-500"></i>
                    <span>{{ $books->total() }} sách</span>
                </div>
                <div class="flex items-center space-x-1">
                    <i class="fas fa-users text-green-500"></i>
                    <span>{{ auth()->check() ? 'Đã đăng nhập' : 'Chưa đăng nhập' }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section với tìm kiếm -->
            <div class="mb-12 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 rounded-3xl"></div>
                <div class="relative p-8 md:p-12">
                    <div class="text-center mb-8">
                        <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-4 animate-fade-in">
                            Khám Phá Thế Giới Tri Thức
                        </h1>
                        <p class="text-xl text-gray-600 mb-8 animate-fade-in-delay">
                            Hàng nghìn cuốn sách và tài liệu đang chờ bạn khám phá
                        </p>
                    </div>
                    
                    <!-- Form tìm kiếm nâng cấp -->
                    <div class="max-w-2xl mx-auto">
                        <form action="{{ route('home') }}" method="GET" class="relative">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400 text-lg"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       placeholder="Tìm kiếm theo tiêu đề, tác giả hoặc danh mục..." 
                                       class="w-full pl-12 pr-32 py-4 text-lg border-2 border-gray-200 rounded-2xl shadow-lg focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 hover:shadow-xl" 
                                       value="{{ request('search') }}">
                                <button type="submit" 
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 px-6 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-search mr-2"></i>
                                    Tìm Kiếm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- === BẮT ĐẦU SLIDER SÁCH PHỔ BIẾN (PHIÊN BẢN MỚI) === -->
            @if($popularBooks->count() > 0)
                <div class="mb-16">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-fire text-white"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800">Sách Phổ Biến</h2>
                        </div>
                        <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                            <i class="fas fa-chart-line text-indigo-500"></i>
                            <span>Dựa trên lượt mượn</span>
                        </div>
                    </div>
                    
                    <div class="swiper popular-books-slider">
                        <div class="swiper-wrapper">
                            @foreach($popularBooks as $index => $book)
                                <div class="swiper-slide h-full">
                                    <div class="group relative">
                                        {{-- Badge xếp hạng --}}
                                        <div class="absolute -top-2 -left-2 z-10 w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            {{ $index + 1 }}
                                        </div>
                                        
                                        {{-- Card chính --}}
                                        <a href="{{ route('books.show', $book) }}" class="flex bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 ease-in-out overflow-hidden h-64 group-hover:scale-105">
                                            <!-- Ảnh bên trái -->
                                            <div class="w-1/3 flex-shrink-0 relative overflow-hidden">
                                                <img class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500" 
                                                    src="{{ $book->cover_url }}" 
                                                     alt="Bìa sách {{ $book->title }}">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            </div>
                                            
                                            <!-- Nội dung bên phải -->
                                            <div class="w-2/3 p-6 flex flex-col">
                                                {{-- 1. Tiêu đề hiển thị ngay, in đậm --}}
                                                <h3 class="text-xl font-bold text-gray-900 line-clamp-2 group-hover:text-indigo-600 transition-colors duration-300">{{ $book->title }}</h3>
                                                <p class="text-sm text-gray-600 mt-2 flex-shrink-0 flex items-center">
                                                    <i class="fas fa-user-edit mr-2 text-indigo-500"></i>
                                                    {{ $book->author }}
                                                </p>
                                                
                                                {{-- 2. Thêm danh mục --}}
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @foreach($book->categories->take(2) as $category)
                                                        <span class="text-xs bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 px-3 py-1 rounded-full font-medium">
                                                            {{ $category->name }}
                                                        </span>
                                                    @endforeach
                                                </div>

                                                {{-- Thống kê --}}
                                                <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500">
                                                    <div class="flex items-center space-x-1">
                                                        <i class="fas fa-book-open text-green-500"></i>
                                                        <span>{{ $book->loans_count ?? 0 }} lượt mượn</span>
                                                    </div>
                                                    @if($book->type == 'physical')
                                                        <div class="flex items-center space-x-1">
                                                            <i class="fas fa-box text-blue-500"></i>
                                                            <span>{{ $book->quantity }} cuốn</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Đẩy nút xuống dưới cùng --}}
                                                <div class="flex-grow"></div>

                                                {{-- 3. Nút "Xem Chi Tiết" --}}
                                                <div class="mt-4 flex-shrink-0">
                                                    <span class="inline-flex items-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold text-sm px-6 py-3 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                                        <i class="fas fa-eye mr-2"></i>
                                                        Xem Chi Tiết
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination mt-6"></div>
                    </div>
                </div>

                <style>
                    .popular-books-slider { 
                        width: 100%; 
                        padding-bottom: 40px !important; 
                    }
                    .swiper-pagination-bullet { 
                        background-color: #cbd5e1 !important;
                        opacity: 1 !important;
                        width: 12px !important;
                        height: 12px !important;
                    }
                    .swiper-pagination-bullet-active { 
                        background: linear-gradient(45deg, #4f46e5, #7c3aed) !important;
                        transform: scale(1.2) !important;
                    }
                </style>
            @endif
            <!-- === KẾT THÚC SLIDER SÁCH PHỔ BIẾN === -->


            <!-- Lưới sách chính nâng cấp -->
            <div class="bg-white overflow-hidden shadow-xl rounded-3xl">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-th-large text-white"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800">Khám Phá Thêm</h2>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <i class="fas fa-sort-amount-down text-indigo-500"></i>
                            <span>Sắp xếp theo mới nhất</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @forelse ($books as $index => $book)
                            <div class="group animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                                <a href="{{ route('books.show', $book) }}" class="block">
                                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 ease-in-out overflow-hidden h-full flex flex-col group-hover:scale-105 group-hover:-translate-y-2">
                                        <!-- Ảnh bìa với overlay -->
                                        <div class="relative flex-shrink-0 overflow-hidden">
                                            <img class="h-64 w-full object-cover group-hover:scale-110 transition-transform duration-500" 
                                                src="{{ $book->cover_url }}" 
                                                 alt="Bìa sách {{ $book->title }}">
                                            
                                            <!-- Overlay gradient -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            
                                            <!-- Badge loại sách -->
                                            <div class="absolute top-3 right-3">
                                                @if($book->type == 'online')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white shadow-lg">
                                                        <i class="fas fa-laptop mr-1"></i>
                                                        Online
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500 text-white shadow-lg">
                                                        <i class="fas fa-book mr-1"></i>
                                                        Vật Lý
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Nút yêu thích -->
                                            @auth
                                                <div class="absolute top-3 left-3">
                                                    <button class="w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors duration-200">
                                                        <i class="fas fa-heart text-gray-400 hover:text-red-500"></i>
                                                    </button>
                                                </div>
                                            @endauth
                                        </div>
                                        
                                        <!-- Nội dung card -->
                                        <div class="p-6 flex flex-col flex-grow">
                                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300 line-clamp-2">
                                                {{ Str::limit($book->title, 50) }}
                                            </h3>
                                            
                                            <p class="text-sm text-gray-600 mt-2 flex items-center">
                                                <i class="fas fa-user-edit mr-2 text-indigo-500"></i>
                                                {{ Str::limit($book->author, 30) }}
                                            </p>
                                            
                                            <!-- Danh mục -->
                                            <div class="mt-3 flex flex-wrap gap-1">
                                                @foreach($book->categories->take(2) as $category)
                                                    <span class="text-xs bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 px-2 py-1 rounded-full font-medium">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                            
                                            <!-- Mô tả ngắn -->
                                            @if($book->description)
                                                <p class="text-sm text-gray-500 mt-3 line-clamp-2">
                                                    {{ Str::limit($book->description, 80) }}
                                                </p>
                                            @endif
                                            
                                            <!-- Thông tin số lượng -->
                                            <div class="mt-auto pt-4">
                                                @if($book->type == 'physical')
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-1 text-sm {{ $book->quantity > 0 ? 'text-green-600' : 'text-red-500' }}">
                                                            <i class="fas fa-box"></i>
                                                            <span class="font-semibold">
                                                                {{ $book->quantity > 0 ? 'Còn ' . $book->quantity . ' cuốn' : 'Hết sách' }}
                                                            </span>
                                                        </div>
                                                        @if($book->quantity > 0)
                                                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="flex items-center space-x-1 text-sm text-green-600">
                                                        <i class="fas fa-download"></i>
                                                        <span class="font-semibold">Có sẵn ngay</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-16">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-search text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-600 mb-2">Không tìm thấy sách nào</h3>
                                <p class="text-gray-500">Hãy thử tìm kiếm với từ khóa khác hoặc duyệt qua danh mục</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Pagination nâng cấp -->
                    @if($books->hasPages())
                        <div class="mt-12 flex justify-center">
                            <div class="bg-white rounded-2xl shadow-lg p-4">
                                {{ $books->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- CSS Animations tùy chỉnh --}}
    @push('styles')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-fade-in-delay {
            animation: fadeIn 0.8s ease-out 0.3s both;
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out both;
        }
        
        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Hover effects */
        .group:hover .group-hover\:scale-105 {
            transform: scale(1.05);
        }
        
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }
        
        .group:hover .group-hover\:-translate-y-2 {
            transform: translateY(-8px);
        }
        
        /* Gradient text */
        .bg-clip-text {
            -webkit-background-clip: text;
            background-clip: text;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #4f46e5, #7c3aed);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #3730a3, #6d28d9);
        }
    </style>
    @endpush

    {{-- Script cho Swiper và animations --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Swiper configuration
            if (document.querySelector('.popular-books-slider')) {
                const swiper = new Swiper('.popular-books-slider', {
                    loop: true,
                    slidesPerView: 1,
                    spaceBetween: 30,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                        dynamicBullets: true,
                    },
                    breakpoints: {
                        768: { slidesPerView: 2, spaceBetween: 20 },
                        1024: { slidesPerView: 2, spaceBetween: 30 }
                    },
                    effect: 'slide',
                    speed: 600,
                });
            }
            
            // Intersection Observer cho animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            // Observe all animated elements
            document.querySelectorAll('.animate-fade-in-up').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease-out';
                observer.observe(el);
            });
            
            // Add smooth scrolling to all links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>