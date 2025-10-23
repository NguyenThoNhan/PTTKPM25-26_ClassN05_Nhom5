<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <i class="fas fa-comments text-blue-500 text-2xl"></i>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Thảo luận
                </h2>
            </div>
            <a href="{{ route('discussions.create') }}" 
               class="bg-gradient-to-r from-green-500 to-teal-600 text-white px-6 py-3 rounded-xl hover:from-green-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>
                Tạo thảo luận mới
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Tìm kiếm thảo luận..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="min-w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loại thảo luận</label>
                        <select name="type" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ request('type') === 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="general" {{ request('type') === 'general' ? 'selected' : '' }}>Chung</option>
                            <option value="book_discussion" {{ request('type') === 'book_discussion' ? 'selected' : '' }}>Thảo luận sách</option>
                            <option value="reading_group" {{ request('type') === 'reading_group' ? 'selected' : '' }}>Nhóm đọc</option>
                        </select>
                    </div>
                    
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Tìm kiếm
                    </button>
                    
                    @if(request()->hasAny(['search', 'type']))
                        <a href="{{ route('discussions.index') }}" 
                           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Xóa bộ lọc
                        </a>
                    @endif
                </form>
            </div>

            <!-- Discussions List -->
            <div class="space-y-6">
                @forelse($discussions as $discussion)
                    <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                {{ substr($discussion->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <h3 class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">
                                        <a href="{{ route('discussions.show', $discussion) }}">{{ $discussion->title }}</a>
                                    </h3>
                                    @if($discussion->is_pinned)
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                            <i class="fas fa-thumbtack mr-1"></i>Ghim
                                        </span>
                                    @endif
                                    @if($discussion->is_locked)
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                            <i class="fas fa-lock mr-1"></i>Khóa
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $discussion->user->name }}</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $discussion->created_at->diffForHumans() }}</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-tag"></i>
                                        <span class="capitalize">{{ $discussion->type }}</span>
                                    </span>
                                    @if($discussion->book)
                                        <a href="{{ route('books.show', $discussion->book) }}" 
                                           class="flex items-center space-x-1 text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-book"></i>
                                            <span>{{ Str::limit($discussion->book->title, 30) }}</span>
                                        </a>
                                    @endif
                                </div>
                                
                                <p class="text-gray-700 mb-4 leading-relaxed">{{ Str::limit(strip_tags($discussion->content), 300) }}</p>
                                
                                @if($discussion->tags)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($discussion->tags as $tag)
                                            <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full hover:bg-gray-200 transition-colors duration-200">
                                                #{{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                                        <span class="flex items-center space-x-2">
                                            <i class="fas fa-eye"></i>
                                            <span>{{ $discussion->views_count }}</span>
                                        </span>
                                        <span class="flex items-center space-x-2">
                                            <i class="fas fa-heart"></i>
                                            <span>{{ $discussion->likes_count }}</span>
                                        </span>
                                        <span class="flex items-center space-x-2">
                                            <i class="fas fa-comment"></i>
                                            <span>{{ $discussion->replies_count }}</span>
                                        </span>
                                    </div>
                                    
                                    <a href="{{ route('discussions.show', $discussion) }}" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-arrow-right mr-2"></i>
                                        Xem thảo luận
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                        <i class="fas fa-comments text-gray-300 text-6xl mb-6"></i>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Chưa có thảo luận nào</h3>
                        <p class="text-gray-600 mb-8">Hãy tạo thảo luận đầu tiên để bắt đầu cuộc trò chuyện!</p>
                        <a href="{{ route('discussions.create') }}" 
                           class="bg-gradient-to-r from-green-500 to-teal-600 text-white px-8 py-3 rounded-xl hover:from-green-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus mr-2"></i>
                            Tạo thảo luận đầu tiên
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($discussions->hasPages())
                <div class="mt-8">
                    {{ $discussions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
