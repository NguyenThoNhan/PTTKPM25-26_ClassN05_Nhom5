<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <i class="fas fa-trophy text-yellow-500 text-2xl"></i>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-yellow-500 to-orange-500 bg-clip-text text-transparent">
                    Gamification Dashboard
                </h2>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('gamification.leaderboard') }}" 
                   class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-300">
                    <i class="fas fa-crown mr-2"></i>
                    Bảng xếp hạng
                </a>
                <a href="{{ route('gamification.badges') }}" 
                   class="bg-gradient-to-r from-green-500 to-teal-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-teal-700 transition-all duration-300">
                    <i class="fas fa-medal mr-2"></i>
                    Huy hiệu
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- User Level & Progress -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ $stats['level'] }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Level {{ $stats['level'] }}</h3>
                            <p class="text-gray-600">{{ $stats['experience_points'] }} điểm kinh nghiệm</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Vị trí #{{ $userPosition }}</p>
                        <p class="text-lg font-semibold text-gray-800">Trên bảng xếp hạng</p>
                    </div>
                </div>
                
                <!-- Level Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-4 rounded-full transition-all duration-1000 ease-out" 
                         style="width: {{ $stats['level_progress'] }}%"></div>
                </div>
                <p class="text-sm text-gray-600 text-center">
                    {{ number_format($stats['level_progress'], 1) }}% hoàn thành level {{ $stats['level'] + 1 }}
                </p>
            </div>

            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Books Read -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-blue-600">{{ $stats['total_books_read'] }}</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-1">Sách đã đọc</h4>
                    <p class="text-sm text-gray-600">Tổng số cuốn sách đã hoàn thành</p>
                </div>

                <!-- Current Streak -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6 border border-red-200 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-fire text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-red-600">{{ $stats['current_streak'] }}</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-1">Chuỗi đọc hiện tại</h4>
                    <p class="text-sm text-gray-600">Số ngày liên tiếp đọc sách</p>
                </div>

                <!-- Max Streak -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-trophy text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-purple-600">{{ $stats['max_streak'] }}</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-1">Chuỗi đọc tốt nhất</h4>
                    <p class="text-sm text-gray-600">Kỷ lục chuỗi đọc dài nhất</p>
                </div>

                <!-- Reviews Written -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 border border-green-200 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-green-600">{{ $stats['total_reviews'] }}</span>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-1">Đánh giá đã viết</h4>
                    <p class="text-sm text-gray-600">Số đánh giá đã chia sẻ</p>
                </div>
            </div>

            <!-- Daily Challenges -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-tasks text-blue-500 mr-3"></i>
                        Thử thách hôm nay
                    </h3>
                    <a href="{{ route('gamification.challenges') }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($challenges as $key => $challenge)
                        <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-300 transition-all duration-300 {{ $challenge['completed'] ? 'bg-green-50 border-green-300' : '' }}">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-gray-800">{{ $challenge['title'] }}</h4>
                                @if($challenge['completed'])
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                @else
                                    <span class="text-sm text-gray-500">{{ $challenge['current'] }}/{{ $challenge['target'] }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mb-4">{{ $challenge['description'] }}</p>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500" 
                                     style="width: {{ min(100, ($challenge['current'] / $challenge['target']) * 100) }}%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">
                                    @if($challenge['completed'])
                                        Hoàn thành!
                                    @else
                                        {{ $challenge['current'] }}/{{ $challenge['target'] }}
                                    @endif
                                </span>
                                <span class="text-sm text-yellow-600 font-medium">
                                    +{{ $challenge['reward'] }} XP
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Badges -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-medal text-yellow-500 mr-3"></i>
                        Huy hiệu gần đây
                    </h3>
                    <a href="{{ route('gamification.badges') }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($badges->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($badges->take(6) as $badge)
                            <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl border border-yellow-200 hover:shadow-lg transition-all duration-300">
                                <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-medal text-white text-xl"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 text-sm mb-1">{{ $badge->name }}</h4>
                                <p class="text-xs text-gray-600">{{ Str::limit($badge->description, 30) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-medal text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">Chưa có huy hiệu nào. Hãy bắt đầu đọc sách để kiếm huy hiệu đầu tiên!</p>
                    </div>
                @endif
            </div>

            <!-- Leaderboard Preview -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-crown text-yellow-500 mr-3"></i>
                        Top 5 người đọc
                    </h3>
                    <a href="{{ route('gamification.leaderboard') }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        Xem bảng xếp hạng đầy đủ <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach($leaderboard->take(5) as $index => $topUser)
                        <div class="flex items-center space-x-4 p-4 rounded-xl {{ $topUser->id === $user->id ? 'bg-blue-50 border-2 border-blue-200' : 'bg-gray-50' }}">
                            <div class="flex-shrink-0">
                                @if($index === 0)
                                    <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-crown text-white text-sm"></i>
                                    </div>
                                @elseif($index === 1)
                                    <div class="w-8 h-8 bg-gradient-to-r from-gray-400 to-gray-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                    </div>
                                @elseif($index === 2)
                                    <div class="w-8 h-8 bg-gradient-to-r from-orange-400 to-orange-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-bold text-sm">{{ $index + 1 }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ $topUser->name }}</h4>
                                <p class="text-sm text-gray-600">Level {{ $topUser->level }} • {{ $topUser->experience_points }} XP</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-700">{{ $topUser->getTotalBooksRead() }} sách</p>
                                <p class="text-xs text-gray-500">{{ $topUser->max_reading_streak }} ngày streak</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-refresh stats every 30 seconds
        setInterval(function() {
            fetch('{{ route("gamification.stats") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update level progress
                        const progressBar = document.querySelector('.bg-gradient-to-r.from-yellow-400.to-orange-500');
                        if (progressBar) {
                            progressBar.style.width = data.stats.level_progress + '%';
                        }
                        
                        // Update level number
                        const levelElement = document.querySelector('.text-2xl.font-bold.text-gray-800');
                        if (levelElement) {
                            levelElement.textContent = 'Level ' + data.stats.level;
                        }
                    }
                })
                .catch(error => console.log('Stats refresh failed:', error));
        }, 30000);
    </script>
    @endpush
</x-app-layout>
