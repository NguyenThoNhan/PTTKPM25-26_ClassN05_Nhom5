<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('notifications.index') }}" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
                <div class="h-6 w-px bg-gray-300"></div>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Cài đặt thông báo
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-8 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Cài đặt thông báo</h1>
                            <p class="text-gray-600">Tùy chỉnh cách bạn nhận thông báo từ BookHaven</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-8">
                    <form action="{{ route('notifications.update-preferences') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <!-- Email Notifications Toggle -->
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Thông báo qua Email</h3>
                                    <p class="text-gray-600">Nhận thông báo qua email khi có hoạt động mới</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="email_notifications_enabled" 
                                           value="1"
                                           {{ $user->email_notifications_enabled ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Notification Types -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Loại thông báo</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Loan Due -->
                                <div class="border border-gray-200 rounded-xl p-6 hover:border-blue-300 transition-colors duration-200">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-clock text-yellow-500 text-xl"></i>
                                        <h4 class="font-semibold text-gray-800">Sách sắp đến hạn</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Nhận thông báo khi sách sắp đến hạn trả</p>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="preferences[loan_due]" 
                                               value="1"
                                               {{ ($preferences['loan_due'] ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <!-- Loan Overdue -->
                                <div class="border border-gray-200 rounded-xl p-6 hover:border-red-300 transition-colors duration-200">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                                        <h4 class="font-semibold text-gray-800">Sách quá hạn</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Nhận thông báo khi sách đã quá hạn trả</p>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="preferences[loan_overdue]" 
                                               value="1"
                                               {{ ($preferences['loan_overdue'] ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-red-600"></div>
                                    </label>
                                </div>

                                <!-- Book Returned -->
                                <div class="border border-gray-200 rounded-xl p-6 hover:border-green-300 transition-colors duration-200">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                        <h4 class="font-semibold text-gray-800">Đã trả sách</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Nhận thông báo khi trả sách thành công</p>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="preferences[book_returned]" 
                                               value="1"
                                               {{ ($preferences['book_returned'] ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>

                                <!-- New Discussion -->
                                <div class="border border-gray-200 rounded-xl p-6 hover:border-blue-300 transition-colors duration-200">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-comments text-blue-500 text-xl"></i>
                                        <h4 class="font-semibold text-gray-800">Thảo luận mới</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Nhận thông báo khi có thảo luận mới trong nhóm</p>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="preferences[new_discussion]" 
                                               value="1"
                                               {{ ($preferences['new_discussion'] ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <!-- New Reply -->
                                <div class="border border-gray-200 rounded-xl p-6 hover:border-purple-300 transition-colors duration-200">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-reply text-purple-500 text-xl"></i>
                                        <h4 class="font-semibold text-gray-800">Phản hồi mới</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Nhận thông báo khi có phản hồi cho thảo luận của bạn</p>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="preferences[new_reply]" 
                                               value="1"
                                               {{ ($preferences['new_reply'] ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>

                                <!-- Reading Group Invite -->
                                <div class="border border-gray-200 rounded-xl p-6 hover:border-indigo-300 transition-colors duration-200">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-users text-indigo-500 text-xl"></i>
                                        <h4 class="font-semibold text-gray-800">Lời mời nhóm đọc</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Nhận thông báo khi được mời tham gia nhóm đọc</p>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="preferences[reading_group_invite]" 
                                               value="1"
                                               {{ ($preferences['reading_group_invite'] ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>

                                <!-- Badge Earned -->
                                <div class="border border-gray-200 rounded-xl p-6 hover:border-yellow-300 transition-colors duration-200">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-medal text-yellow-500 text-xl"></i>
                                        <h4 class="font-semibold text-gray-800">Nhận huy hiệu</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Nhận thông báo khi nhận được huy hiệu mới</p>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="preferences[badge_earned]" 
                                               value="1"
                                               {{ ($preferences['badge_earned'] ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-yellow-600"></div>
                                    </label>
                                </div>

                                <!-- Level Up -->
                                <div class="border border-gray-200 rounded-xl p-6 hover:border-orange-300 transition-colors duration-200">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <i class="fas fa-star text-orange-500 text-xl"></i>
                                        <h4 class="font-semibold text-gray-800">Lên cấp</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">Nhận thông báo khi lên cấp trong hệ thống</p>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="preferences[level_up]" 
                                               value="1"
                                               {{ ($preferences['level_up'] ?? true) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('notifications.index') }}" 
                               class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-300">
                                Hủy
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i>
                                Lưu cài đặt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
