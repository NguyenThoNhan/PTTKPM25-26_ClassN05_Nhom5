<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <i class="fas fa-bell text-blue-500 text-2xl"></i>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Thông báo
                </h2>
                @if($unreadCount > 0)
                    <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                @endif
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('notifications.preferences') }}" 
                   class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300">
                    <i class="fas fa-cog mr-2"></i>
                    Cài đặt
                </a>
                @if($unreadCount > 0)
                    <button onclick="markAllAsRead()" 
                            class="bg-gradient-to-r from-green-500 to-teal-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-teal-700 transition-all duration-300">
                        <i class="fas fa-check-double mr-2"></i>
                        Đánh dấu tất cả đã đọc
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 {{ !$notification->is_read ? 'border-l-4 border-blue-500' : '' }}">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $notification->color === 'yellow' ? 'bg-yellow-100' : ($notification->color === 'red' ? 'bg-red-100' : ($notification->color === 'green' ? 'bg-green-100' : ($notification->color === 'blue' ? 'bg-blue-100' : ($notification->color === 'purple' ? 'bg-purple-100' : 'bg-gray-100')))) }}">
                                        <i class="{{ $notification->icon }} text-xl"></i>
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                            {{ $notification->title }}
                                        </h3>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-500">{{ $notification->time_ago }}</span>
                                            @if(!$notification->is_read)
                                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-700 mb-3">{{ $notification->message }}</p>
                                    
                                    @if($notification->data)
                                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                            @foreach($notification->data as $key => $value)
                                                @if($key === 'book_id' && $value)
                                                    <p class="text-sm text-gray-600">
                                                        <strong>Sách:</strong> {{ \App\Models\Book::find($value)->title ?? 'N/A' }}
                                                    </p>
                                                @elseif($key === 'due_date' && $value)
                                                    <p class="text-sm text-gray-600">
                                                        <strong>Hạn trả:</strong> {{ \Carbon\Carbon::parse($value)->format('d/m/Y') }}
                                                    </p>
                                                @elseif($key === 'days_overdue' && $value)
                                                    <p class="text-sm text-red-600">
                                                        <strong>Số ngày quá hạn:</strong> {{ $value }} ngày
                                                    </p>
                                                @elseif($key === 'experience_points' && $value)
                                                    <p class="text-sm text-green-600">
                                                        <strong>Điểm kinh nghiệm:</strong> +{{ $value }} XP
                                                    </p>
                                                @elseif($key === 'new_level' && $value)
                                                    <p class="text-sm text-orange-600">
                                                        <strong>Cấp độ mới:</strong> Level {{ $value }}
                                                    </p>
                                                @elseif($key === 'badge_name' && $value)
                                                    <p class="text-sm text-yellow-600">
                                                        <strong>Huy hiệu:</strong> {{ $value }}
                                                    </p>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center space-x-4">
                                        @if(!$notification->is_read)
                                            <button onclick="markAsRead({{ $notification->id }})" 
                                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                <i class="fas fa-check mr-1"></i>
                                                Đánh dấu đã đọc
                                            </button>
                                        @endif
                                        
                                        <button onclick="deleteNotification({{ $notification->id }})" 
                                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            <i class="fas fa-trash mr-1"></i>
                                            Xóa
                                        </button>
                                        
                                        @if($notification->email_sent)
                                            <span class="text-green-600 text-sm">
                                                <i class="fas fa-envelope mr-1"></i>
                                                Đã gửi email
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="mt-8">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                    <i class="fas fa-bell-slash text-gray-300 text-6xl mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Chưa có thông báo nào</h3>
                    <p class="text-gray-600 mb-8">Bạn sẽ nhận được thông báo khi có hoạt động mới trong tài khoản của mình.</p>
                    <a href="{{ route('home') }}" 
                       class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-home mr-2"></i>
                        Về trang chủ
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function markAllAsRead() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function deleteNotification(notificationId) {
            if (confirm('Bạn có chắc chắn muốn xóa thông báo này?')) {
                fetch(`/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
    @endpush
</x-app-layout>
