<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <i class="fas fa-users text-blue-500 text-2xl"></i>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Social Feed
                </h2>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('discussions.create') }}" 
                   class="bg-gradient-to-r from-green-500 to-teal-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-teal-700 transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>
                    Tạo thảo luận
                </a>
                <a href="{{ route('reading-groups.create') }}" 
                   class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-4 py-2 rounded-lg hover:from-purple-600 hover:to-pink-700 transition-all duration-300">
                    <i class="fas fa-users mr-2"></i>
                    Tạo nhóm đọc
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Recent Discussions -->
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-comments text-blue-500 mr-3"></i>
                                Thảo luận gần đây
                            </h3>
                            <a href="{{ route('discussions.index') }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>

                        <div class="space-y-6">
                            @forelse($discussions as $discussion)
                                <div class="border-b border-gray-200 pb-6 last:border-b-0 hover:bg-gray-50 rounded-lg p-4 transition-colors duration-200">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ substr($discussion->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <h4 class="font-semibold text-gray-800">{{ $discussion->user->name }}</h4>
                                                <span class="text-sm text-gray-500">{{ $discussion->created_at->diffForHumans() }}</span>
                                                @if($discussion->is_pinned)
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                                        <i class="fas fa-thumbtack mr-1"></i>Ghim
                                                    </span>
                                                @endif
                                                @if($discussion->book)
                                                    <a href="{{ route('books.show', $discussion->book) }}" 
                                                       class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full hover:bg-blue-200 transition-colors duration-200">
                                                        <i class="fas fa-book mr-1"></i>{{ Str::limit($discussion->book->title, 20) }}
                                                    </a>
                                                @endif
                                            </div>
                                            <h5 class="text-lg font-semibold text-gray-900 mb-2 hover:text-blue-600 transition-colors duration-200">
                                                <a href="{{ route('discussions.show', $discussion) }}">{{ $discussion->title }}</a>
                                            </h5>
                                            <p class="text-gray-600 mb-3">{{ Str::limit(strip_tags($discussion->content), 200) }}</p>
                                            
                                            @if($discussion->tags)
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    @foreach($discussion->tags as $tag)
                                                        <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">#{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center space-x-6 text-sm text-gray-500">
                                                <span class="flex items-center space-x-1">
                                                    <i class="fas fa-eye"></i>
                                                    <span>{{ $discussion->views_count }}</span>
                                                </span>
                                                <span class="flex items-center space-x-1">
                                                    <i class="fas fa-heart"></i>
                                                    <span>{{ $discussion->likes_count }}</span>
                                                </span>
                                                <span class="flex items-center space-x-1">
                                                    <i class="fas fa-comment"></i>
                                                    <span>{{ $discussion->replies_count }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <i class="fas fa-comments text-gray-300 text-4xl mb-4"></i>
                                    <p class="text-gray-500">Chưa có thảo luận nào. Hãy tạo thảo luận đầu tiên!</p>
                                </div>
                            @endforelse
                        </div>

                        @if($discussions->hasPages())
                            <div class="mt-6">
                                {{ $discussions->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- My Reading Groups -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-users text-purple-500 mr-2"></i>
                            Nhóm đọc của tôi
                        </h3>
                        
                        @if($userGroups->count() > 0)
                            <div class="space-y-3">
                                @foreach($userGroups->take(3) as $group)
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                            {{ substr($group->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-medium text-gray-800 truncate">{{ $group->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $group->members_count }} thành viên</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('reading-groups.index') }}" 
                                   class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                                    Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users text-gray-300 text-2xl mb-2"></i>
                                <p class="text-gray-500 text-sm">Chưa tham gia nhóm nào</p>
                                <a href="{{ route('reading-groups.index') }}" 
                                   class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                                    Khám phá nhóm đọc
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Popular Reading Groups -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-fire text-orange-500 mr-2"></i>
                            Nhóm phổ biến
                        </h3>
                        
                        <div class="space-y-3">
                            @foreach($popularGroups as $group)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        {{ substr($group->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-800 truncate">{{ $group->name }}</h4>
                                        <p class="text-xs text-gray-500">{{ $group->members_count }} thành viên</p>
                                    </div>
                                    <a href="{{ route('reading-groups.show', $group) }}" 
                                       class="text-orange-600 hover:text-orange-800 text-sm">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-6 border border-blue-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Hành động nhanh</h3>
                        <div class="space-y-3">
                            <a href="{{ route('discussions.create') }}" 
                               class="flex items-center space-x-3 p-3 bg-white rounded-lg hover:shadow-md transition-all duration-200">
                                <i class="fas fa-comment-plus text-green-500"></i>
                                <span class="text-gray-700">Tạo thảo luận</span>
                            </a>
                            <a href="{{ route('reading-groups.create') }}" 
                               class="flex items-center space-x-3 p-3 bg-white rounded-lg hover:shadow-md transition-all duration-200">
                                <i class="fas fa-users text-purple-500"></i>
                                <span class="text-gray-700">Tạo nhóm đọc</span>
                            </a>
                            <a href="{{ route('social.search-users') }}" 
                               class="flex items-center space-x-3 p-3 bg-white rounded-lg hover:shadow-md transition-all duration-200">
                                <i class="fas fa-search text-blue-500"></i>
                                <span class="text-gray-700">Tìm người dùng</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
