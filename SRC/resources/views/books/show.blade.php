<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 text-gray-600 hover:text-indigo-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
                <div class="h-6 w-px bg-gray-300"></div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent line-clamp-1">
                    {{ $book->title }}
                </h2>
            </div>
            <div class="hidden md:flex items-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center space-x-1">
                    <i class="fas fa-eye text-indigo-500"></i>
                    <span>Chi tiết sách</span>
                </div>
                @if($book->type == 'online')
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-laptop text-green-500"></i>
                        <span>Tài liệu Online</span>
                    </div>
                @else
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-book text-blue-500"></i>
                        <span>Sách Vật Lý</span>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-3xl">
                <div class="p-8 text-gray-900">
                    
                    <!-- Thông báo nâng cấp -->
                    @if (session('success'))
                        <div class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 p-6 rounded-2xl shadow-lg animate-slide-in-left" role="alert">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-2xl text-green-500"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="font-bold text-lg">Thành công!</p>
                                    <p class="text-sm">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-8 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-800 p-6 rounded-2xl shadow-lg animate-slide-in-left" role="alert">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-2xl text-red-500"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="font-bold text-lg">Lỗi!</p>
                                    <p class="text-sm">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="md:flex gap-8">
                        <!-- Cột trái: Ảnh bìa nâng cấp -->
                        <div class="md:w-1/3">
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                                <div class="relative bg-white rounded-2xl p-2">
                                    <img class="rounded-xl shadow-2xl w-full h-auto group-hover:scale-105 transition-transform duration-500" 
                                        src="{{ $book->cover_url }}" 
                                         alt="Bìa sách {{ $book->title }}">
                                </div>
                                
                                <!-- Badge loại sách -->
                                <div class="absolute top-4 right-4">
                                    @if($book->type == 'online')
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-500 text-white shadow-lg">
                                            <i class="fas fa-laptop mr-2"></i>
                                            Tài liệu Online
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-500 text-white shadow-lg">
                                            <i class="fas fa-book mr-2"></i>
                                            Sách Vật Lý
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Cột phải: Thông tin & Hành động nâng cấp -->
                        <div class="md:w-2/3 mt-8 md:mt-0">
                            <!-- === TIÊU ĐỀ VÀ NÚT YÊU THÍCH === -->
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex-1">
                                    <h1 class="text-4xl font-bold text-gray-900 mb-2 animate-fade-in">{{ $book->title }}</h1>
                                    <div class="flex items-center space-x-4 text-lg text-gray-600">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-user-edit text-indigo-500"></i>
                                            <span>bởi <span class="font-semibold text-gray-800">{{ $book->author }}</span></span>
                                        </div>
                                    </div>
                                </div>
                                @auth
                                    <button id="favorite-toggle-btn" 
                                            data-book-id="{{ $book->id }}" 
                                            class="flex-shrink-0 w-12 h-12 rounded-full transition-all duration-300 {{ $isFavorited ? 'text-red-500 bg-red-100 shadow-lg' : 'text-gray-400 hover:bg-gray-100 hover:text-red-500' }} hover:scale-110">
                                        <i class="fa-solid fa-heart text-xl"></i>
                                    </button>
                                @endauth
                            </div>
                            
                            <!-- Danh mục nâng cấp -->
                            <div class="mb-6">
                                <div class="flex items-center space-x-2 mb-3">
                                    <i class="fas fa-tags text-indigo-500"></i>
                                    <span class="text-sm font-semibold text-gray-700">Danh mục:</span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($book->categories as $category)
                                        <a href="{{ route('categories.show', $category) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-100 to-purple-100 hover:from-indigo-200 hover:to-purple-200 text-indigo-800 text-sm font-medium rounded-full transition-all duration-300 hover:scale-105 shadow-sm hover:shadow-md">
                                            <i class="fas fa-folder mr-2"></i>
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Mô tả nâng cấp -->
                            @if($book->description)
                                <div class="mb-8">
                                    <div class="flex items-center space-x-2 mb-3">
                                        <i class="fas fa-align-left text-indigo-500"></i>
                                        <span class="text-sm font-semibold text-gray-700">Mô tả:</span>
                                    </div>
                                    <div class="bg-gray-50 rounded-2xl p-6 border-l-4 border-indigo-500">
                                        <p class="text-gray-700 leading-relaxed text-lg">{{ $book->description }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Thông tin Loại sách & Số lượng nâng cấp -->
                            <div class="mb-8">
                                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            @if($book->type == 'online')
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-laptop text-green-600 text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <span class="text-lg font-semibold text-green-800">Tài liệu Online</span>
                                                        <p class="text-sm text-green-600">Có sẵn ngay lập tức</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-book text-blue-600 text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <span class="text-lg font-semibold text-blue-800">Sách Vật Lý</span>
                                                        <p class="text-sm {{ $book->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ $book->quantity > 0 ? 'Còn lại ' . $book->quantity . ' cuốn' : 'Đã hết sách' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        @if($book->type == 'physical' && $book->quantity > 0)
                                            <div class="flex items-center space-x-2">
                                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                                <span class="text-sm text-green-600 font-medium">Có sẵn</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nút hành động Mượn/Trả nâng cấp -->
                            <div class="space-y-4">
                                @auth
                                    @if($currentLoan)
                                        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl p-6 border border-red-200">
                                            <div class="flex items-center space-x-3 mb-4">
                                                <i class="fas fa-info-circle text-red-500 text-xl"></i>
                                                <h3 class="text-lg font-semibold text-red-800">Bạn đang mượn cuốn sách này</h3>
                                            </div>
                                            <form action="{{ route('loans.return', $currentLoan) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" 
                                                        class="w-full flex items-center justify-center px-8 py-4 bg-gradient-to-r from-red-500 to-pink-600 text-white font-semibold text-lg rounded-2xl hover:from-red-600 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                                    <i class="fa-solid fa-right-from-bracket mr-3 text-xl"></i> 
                                                    Trả Lại Sách
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        @if($book->type == 'physical')
                                            @if ($book->quantity > 0)
                                                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                                                    <div class="flex items-center space-x-3 mb-4">
                                                        <i class="fas fa-book-open text-indigo-500 text-xl"></i>
                                                        <h3 class="text-lg font-semibold text-indigo-800">Mượn sách vật lý</h3>
                                                    </div>
                                                    <form action="{{ route('loans.store', $book) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="w-full flex items-center justify-center px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold text-lg rounded-2xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                                            <i class="fa-solid fa-book-bookmark mr-3 text-xl"></i> 
                                                            Mượn Ngay
                                                        </button>
                                                    </form>
                                                    <p class="text-sm text-indigo-600 mt-3 text-center">Thời hạn mượn: 2 tuần</p>
                                                </div>
                                            @else
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-300">
                                                    <div class="flex items-center space-x-3 mb-4">
                                                        <i class="fas fa-exclamation-triangle text-gray-500 text-xl"></i>
                                                        <h3 class="text-lg font-semibold text-gray-700">Sách đã hết</h3>
                                                    </div>
                                                    <button disabled 
                                                            class="w-full flex items-center justify-center px-8 py-4 bg-gray-400 text-white font-semibold text-lg rounded-2xl cursor-not-allowed">
                                                        <i class="fas fa-times mr-3 text-xl"></i> 
                                                        Đã Hết Sách
                                                    </button>
                                                </div>
                                            @endif
                                        @else
                                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                                                <div class="flex items-center space-x-3 mb-4">
                                                    <i class="fas fa-file-signature text-green-500 text-xl"></i>
                                                    <h3 class="text-lg font-semibold text-green-800">Mượn tài liệu online</h3>
                                                </div>
                                                <form id="borrow-form" action="{{ route('loans.store', $book) }}" method="POST" class="hidden">@csrf</form>
                                                <button id="show-signature-modal-btn" 
                                                        type="button" 
                                                        class="w-full flex items-center justify-center px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold text-lg rounded-2xl hover:from-green-600 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                                    <i class="fa-solid fa-signature mr-3 text-xl"></i> 
                                                    Mượn & Áp dụng Chữ Ký Số
                                                </button>
                                                <p class="text-sm text-green-600 mt-3 text-center">Tài liệu sẽ được ký số để đảm bảo tính toàn vẹn</p>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-200">
                                        <div class="flex items-center space-x-3 mb-4">
                                            <i class="fas fa-lock text-yellow-500 text-xl"></i>
                                            <h3 class="text-lg font-semibold text-yellow-800">Cần đăng nhập để mượn sách</h3>
                                        </div>
                                        <a href="{{ route('login') }}" 
                                           class="w-full flex items-center justify-center px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold text-lg rounded-2xl hover:from-yellow-600 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            <i class="fas fa-sign-in-alt mr-3 text-xl"></i> 
                                            Đăng Nhập Ngay
                                        </a>
                                    </div>
                                @endauth
                            </div>
                            
                            @if($book->type == 'online' && $currentLoan)
                                <div class="mt-8">
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                                        <div class="flex items-center space-x-3 mb-4">
                                            <i class="fas fa-file-alt text-blue-500 text-xl"></i>
                                            <h3 class="text-xl font-semibold text-blue-800">Nội dung tài liệu</h3>
                                        </div>
                                        <div class="bg-white rounded-xl p-6 shadow-inner border">
                                            <pre class="whitespace-pre-wrap font-mono text-sm text-gray-700 leading-relaxed">{{ $book->content }}</pre>
                                        </div>
                                        <div class="mt-4 flex items-center space-x-2 text-sm text-blue-600">
                                            <i class="fas fa-info-circle"></i>
                                            <span>Bạn có thể chỉnh sửa nội dung tài liệu trong phần "Tài liệu của tôi"</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Reviews Section -->
                            <div class="mt-8">
                                <div class="bg-white rounded-2xl shadow-lg p-8">
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-star text-yellow-500 text-xl"></i>
                                            <h3 class="text-2xl font-bold text-gray-800">Đánh giá & Nhận xét</h3>
                                        </div>
                                        @auth
                                            @if(!$book->reviews()->where('user_id', auth()->id())->exists())
                                                <a href="{{ route('reviews.create', $book) }}" 
                                                   class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Viết đánh giá
                                                </a>
                                            @endif
                                        @endauth
                                    </div>
                                    
                                    <!-- Rating Summary -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                        <div class="text-center">
                                            <div class="text-5xl font-bold text-gray-800 mb-2">
                                                {{ number_format($book->averageRating(), 1) }}
                                            </div>
                                            <div class="flex justify-center mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($book->averageRating()))
                                                        <i class="fas fa-star text-yellow-400 text-xl"></i>
                                                    @elseif($i - 0.5 <= $book->averageRating())
                                                        <i class="fas fa-star-half-alt text-yellow-400 text-xl"></i>
                                                    @else
                                                        <i class="far fa-star text-gray-300 text-xl"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <p class="text-gray-600">{{ $book->totalReviews() }} đánh giá</p>
                                        </div>
                                        
                                        <!-- Rating Distribution -->
                                        <div class="space-y-2">
                                            @for($rating = 5; $rating >= 1; $rating--)
                                                @php
                                                    $count = $book->reviews()->where('rating', $rating)->count();
                                                    $percentage = $book->totalReviews() > 0 ? ($count / $book->totalReviews()) * 100 : 0;
                                                @endphp
                                                <div class="flex items-center space-x-3">
                                                    <span class="text-sm font-medium w-8">{{ $rating }}★</span>
                                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                    <span class="text-sm text-gray-600 w-8">{{ $count }}</span>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                    
                                    <!-- Recent Reviews -->
                                    <div class="space-y-6">
                                        @forelse($book->reviews()->with('user')->latest()->take(3)->get() as $review)
                                            <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                                <div class="flex items-start space-x-4">
                                                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                                        {{ substr($review->user->name, 0, 1) }}
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <h4 class="font-semibold text-gray-800">{{ $review->user->name }}</h4>
                                                            @if($review->is_verified_purchase)
                                                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                                    <i class="fas fa-check mr-1"></i>Đã mượn
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <div class="flex">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    @if($i <= $review->rating)
                                                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                                    @else
                                                                        <i class="far fa-star text-gray-300 text-sm"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                            <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        @if($review->review_text)
                                                            <p class="text-gray-700 leading-relaxed">{{ $review->review_text }}</p>
                                                        @endif
                                                        @if($review->images)
                                                            <div class="flex space-x-2 mt-3">
                                                                @foreach($review->images as $image)
                                                                    <img src="{{ asset('storage/' . $image) }}" alt="Review image" class="w-16 h-16 object-cover rounded-lg">
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        <div class="flex items-center space-x-4 mt-3">
                                                            <button class="text-sm text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                                                <i class="fas fa-thumbs-up mr-1"></i>
                                                                Hữu ích ({{ $review->helpful_count }})
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-8">
                                                <i class="fas fa-comments text-gray-300 text-4xl mb-4"></i>
                                                <p class="text-gray-500">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá cuốn sách này!</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    
                                    @if($book->totalReviews() > 3)
                                        <div class="text-center mt-6">
                                            <a href="{{ route('reviews.index', $book) }}" 
                                               class="text-indigo-600 hover:text-indigo-800 font-medium">
                                                Xem tất cả {{ $book->totalReviews() }} đánh giá
                                                <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- === MODAL XÁC NHẬN CHỮ KÝ SỐ NÂNG CẤP === -->
    <div id="signature-modal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-6 w-full max-w-lg">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <!-- Header với gradient -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6">
                    <div class="flex items-center justify-center space-x-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-file-signature text-2xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Xác Nhận Chữ Ký Số</h3>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="px-8 py-6">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gradient-to-r from-green-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shield-alt text-3xl text-indigo-600"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Bảo mật tài liệu với chữ ký số</h4>
                        <p class="text-gray-600 leading-relaxed">
                            Hệ thống sẽ tạo chữ ký điện tử để đảm bảo tính toàn vẹn của tài liệu. 
                            Khi trả tài liệu, hệ thống sẽ xác thực xem nội dung có bị thay đổi hay không.
                        </p>
                    </div>
                    
                    <!-- Thông tin chi tiết -->
                    <div class="bg-gray-50 rounded-2xl p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                            <div class="text-sm text-gray-700">
                                <p class="font-medium mb-1">Lợi ích của chữ ký số:</p>
                                <ul class="list-disc list-inside space-y-1 text-gray-600">
                                    <li>Đảm bảo tính toàn vẹn của tài liệu</li>
                                    <li>Phát hiện mọi thay đổi không được phép</li>
                                    <li>Bảo vệ quyền sở hữu trí tuệ</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="space-y-3">
                        <button id="confirm-borrow-btn" 
                                class="w-full flex items-center justify-center px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold text-lg rounded-2xl hover:from-green-600 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fa-solid fa-check mr-3 text-xl"></i> 
                            Đồng ý & Mượn tài liệu
                        </button>
                        <button id="close-modal-btn" 
                                class="w-full flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-medium text-lg rounded-2xl hover:bg-gray-200 transition-all duration-300">
                            <i class="fas fa-times mr-3"></i>
                            Từ chối
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
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
        
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
            40%, 43% { transform: translate3d(0, -8px, 0); }
            70% { transform: translate3d(0, -4px, 0); }
            90% { transform: translate3d(0, -2px, 0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }
        
        .animate-pulse {
            animation: pulse 2s infinite;
        }
        
        .animate-bounce {
            animation: bounce 1s infinite;
        }
        
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Modal animations */
        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }
        
        @keyframes modalEnter {
            from { 
                opacity: 0; 
                transform: translate(-50%, -50%) scale(0.9); 
            }
            to { 
                opacity: 1; 
                transform: translate(-50%, -50%) scale(1); 
            }
        }
        
        /* Hover effects */
        .group:hover .group-hover\:scale-105 {
            transform: scale(1.05);
        }
        
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- LOGIC CHO NÚT YÊU THÍCH NÂNG CẤP ---
            const favoriteButton = document.getElementById('favorite-toggle-btn');
            if (favoriteButton) {
                favoriteButton.addEventListener('click', function() {
                    this.disabled = true;
                    const bookId = this.dataset.bookId;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // Thêm hiệu ứng loading
                    const originalContent = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin text-xl"></i>';

                    fetch(`/favorites/${bookId}/toggle`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.is_favorited) {
                            this.classList.remove('text-gray-400', 'hover:bg-gray-100');
                            this.classList.add('text-red-500', 'bg-red-100', 'shadow-lg');
                            this.innerHTML = '<i class="fa-solid fa-heart text-xl"></i>';
                            // Thêm hiệu ứng bounce
                            this.classList.add('animate-bounce');
                            setTimeout(() => this.classList.remove('animate-bounce'), 1000);
                        } else {
                            this.classList.remove('text-red-500', 'bg-red-100', 'shadow-lg');
                            this.classList.add('text-gray-400', 'hover:bg-gray-100');
                            this.innerHTML = '<i class="fa-solid fa-heart text-xl"></i>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.innerHTML = originalContent;
                    })
                    .finally(() => { 
                        this.disabled = false; 
                    });
                });
            }

            // --- LOGIC CHO MODAL XÁC NHẬN CHỮ KÝ SỐ NÂNG CẤP ---
            const modal = document.getElementById('signature-modal');
            const showModalBtn = document.getElementById('show-signature-modal-btn');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const confirmBorrowBtn = document.getElementById('confirm-borrow-btn');
            const borrowForm = document.getElementById('borrow-form');

            if (showModalBtn) {
                showModalBtn.addEventListener('click', () => { 
                    modal.classList.remove('hidden');
                    modal.classList.add('modal-enter');
                    // Ngăn scroll body khi modal mở
                    document.body.style.overflow = 'hidden';
                });
            }
            
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', () => { 
                    modal.classList.add('hidden');
                    modal.classList.remove('modal-enter');
                    document.body.style.overflow = 'auto';
                });
            }
            
            if (confirmBorrowBtn) {
                confirmBorrowBtn.addEventListener('click', () => {
                    const originalContent = confirmBorrowBtn.innerHTML;
                    confirmBorrowBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-3 text-xl"></i> Đang xử lý...';
                    confirmBorrowBtn.disabled = true;
                    confirmBorrowBtn.classList.add('opacity-75', 'cursor-not-allowed');
                    
                    setTimeout(() => {
                        borrowForm.submit();
                    }, 1000);
                });
            }
            
            // Đóng modal khi click outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('modal-enter');
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Đóng modal với phím Escape
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    modal.classList.remove('modal-enter');
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Smooth scroll cho các link
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