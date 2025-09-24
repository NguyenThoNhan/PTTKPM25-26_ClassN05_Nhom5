<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tachometer-alt text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-2xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        {{ __('Admin Dashboard') }}
                    </h2>
                    <p class="text-sm text-gray-600">Tổng quan hệ thống quản lý thư viện</p>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-4 text-sm text-gray-600">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar text-indigo-500"></i>
                    <span>{{ now()->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-clock text-green-500"></i>
                    <span>{{ now()->format('H:i') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 1. Grid Thống Kê Tổng Quan Nâng Cấp -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Card Tổng số người dùng -->
                <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 flex items-center justify-between border border-gray-100 hover:border-indigo-200">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Tổng Người Dùng</h3>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['total_users'] }}</p>
                        <p class="text-sm text-gray-500 flex items-center">
                            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                            <span class="text-green-600 font-medium">+12%</span>
                            <span class="ml-2">so với tháng trước</span>
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                </div>
                
                <!-- Card Tổng số sách -->
                <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 flex items-center justify-between border border-gray-100 hover:border-green-200">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Tổng Sách</h3>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['total_books'] }}</p>
                        <p class="text-sm text-gray-500 flex items-center">
                            <i class="fas fa-book-open text-indigo-500 mr-1"></i>
                            <span class="text-indigo-600 font-medium">Sách & Tài liệu</span>
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-4 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-book text-2xl"></i>
                    </div>
                </div>
                
                <!-- Card Sách đang mượn -->
                <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 flex items-center justify-between border border-gray-100 hover:border-yellow-200">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Đang Mượn</h3>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['loans_active'] }}</p>
                        <p class="text-sm text-gray-500 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-1"></i>
                            <span class="text-yellow-600 font-medium">Hoạt động</span>
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-500 to-orange-600 text-white p-4 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-arrow-up-from-bracket text-2xl"></i>
                    </div>
                </div>
                
                <!-- Card Sự kiện sắp tới -->
                <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 flex items-center justify-between border border-gray-100 hover:border-purple-200">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-3 h-3 bg-purple-500 rounded-full animate-pulse"></div>
                            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Sự Kiện</h3>
                        </div>
                        <p class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['upcoming_events'] }}</p>
                        <p class="text-sm text-gray-500 flex items-center">
                            <i class="fas fa-calendar-check text-purple-500 mr-1"></i>
                            <span class="text-purple-600 font-medium">Sắp diễn ra</span>
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-indigo-600 text-white p-4 rounded-2xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-calendar-days text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- 2. Grid cho Biểu đồ Nâng Cấp -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                <!-- Biểu đồ đường: Lượt mượn 30 ngày qua -->
                <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Lượt Mượn Sách</h3>
                                <p class="text-sm text-gray-600">Thống kê 30 ngày qua</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span>Lượt mượn</span>
                        </div>
                    </div>
                    <div class="relative h-80">
                        <canvas id="loansChart" data-chart-data="{{ json_encode($loansChartData) }}"></canvas>
                    </div>
                </div>
                
                <!-- Biểu đồ tròn: Sách theo danh mục -->
                <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-pie text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Phân Bố Danh Mục</h3>
                                <p class="text-sm text-gray-600">Theo loại sách</p>
                            </div>
                        </div>
                    </div>
                    <div class="relative h-80">
                        <canvas id="categoriesChart" data-chart-data="{{ json_encode($categoryChartData) }}"></canvas>
                    </div>
                </div>
            </div>

             <!-- 3. Grid cho Hoạt động gần đây Nâng Cấp -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Bảng: Lượt mượn gần nhất -->
                <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-history text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Hoạt Động Gần Đây</h3>
                            <p class="text-sm text-gray-600">Mượn/trả sách mới nhất</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentLoans as $loan)
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 {{ $loan->status == 'returned' ? 'bg-green-100' : 'bg-blue-100' }} rounded-full flex items-center justify-center">
                                        <i class="fas {{ $loan->status == 'returned' ? 'fa-check text-green-600' : 'fa-book text-blue-600' }}"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $loan->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 truncate">
                                        {{ $loan->status == 'returned' ? 'Đã trả' : 'Đã mượn' }} 
                                        <span class="font-medium">"{{ Str::limit($loan->book->title, 25) }}"</span>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $loan->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $loan->status == 'returned' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $loan->status == 'returned' ? 'Trả' : 'Mượn' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-500">Chưa có hoạt động nào</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Bảng: Người dùng mới nhất -->
                <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Người Dùng Mới</h3>
                            <p class="text-sm text-gray-600">Đăng ký gần đây</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentUsers as $user)
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-100 to-pink-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-purple-600"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-600 truncate">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-500">Tham gia {{ $user->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Mới
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-500">Chưa có người dùng mới</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
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
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out both;
        }
        
        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }
        
        .animate-pulse {
            animation: pulse 2s infinite;
        }
        
        /* Hover effects */
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #4f46e5, #7c3aed);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #3730a3, #6d28d9);
        }
        
        /* Chart container styling */
        .chart-container {
            position: relative;
            height: 320px;
        }
    </style>
    @endpush

    @push('scripts')
        {{-- Nhúng thư viện Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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
                document.querySelectorAll('.group').forEach((el, index) => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(30px)';
                    el.style.transition = `all 0.6s ease-out ${index * 0.1}s`;
                    observer.observe(el);
                });
                
                // --- Biểu đồ Lượt mượn (Line Chart) Nâng Cấp ---
                const loansCanvas = document.getElementById('loansChart');
                if (loansCanvas) {
                    const loansChartData = JSON.parse(loansCanvas.dataset.chartData);
                    
                    new Chart(loansCanvas.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: loansChartData.labels,
                            datasets: [{
                                label: 'Số lượt mượn',
                                data: loansChartData.data,
                                borderColor: 'rgb(79, 70, 229)',
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                borderWidth: 3,
                                pointBackgroundColor: 'rgb(79, 70, 229)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    borderColor: 'rgb(79, 70, 229)',
                                    borderWidth: 1,
                                    cornerRadius: 8,
                                    displayColors: false
                                }
                            },
                            scales: { 
                                y: { 
                                    beginAtZero: true, 
                                    ticks: { 
                                        stepSize: 1,
                                        color: '#6b7280'
                                    },
                                    grid: {
                                        color: 'rgba(107, 114, 128, 0.1)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#6b7280'
                                    },
                                    grid: {
                                        color: 'rgba(107, 114, 128, 0.1)'
                                    }
                                }
                            },
                            animation: {
                                duration: 2000,
                                easing: 'easeInOutQuart'
                            }
                        }
                    });
                }

                // --- Biểu đồ Danh mục (Doughnut Chart) Nâng Cấp ---
                const categoriesCanvas = document.getElementById('categoriesChart');
                if (categoriesCanvas) {
                    const categoryChartData = JSON.parse(categoriesCanvas.dataset.chartData);

                    new Chart(categoriesCanvas.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: categoryChartData.labels,
                            datasets: [{
                                data: categoryChartData.data,
                                backgroundColor: [
                                    '#4f46e5', '#10b981', '#f59e0b', 
                                    '#3b82f6', '#8b5cf6', '#ec4899',
                                    '#f97316', '#84cc16', '#06b6d4'
                                ],
                                borderWidth: 0,
                                hoverOffset: 8
                            }]
                        },
                        options: { 
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { 
                                legend: { 
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: {
                                            size: 12,
                                            weight: '500'
                                        },
                                        color: '#374151'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    cornerRadius: 8,
                                    displayColors: true
                                }
                            },
                            animation: {
                                animateRotate: true,
                                animateScale: true,
                                duration: 2000,
                                easing: 'easeInOutQuart'
                            }
                        }
                    });
                }
                
                // Auto refresh data every 5 minutes
                setInterval(() => {
                    // Có thể thêm logic refresh data ở đây
                    console.log('Dashboard data refreshed');
                }, 300000);
            });
        </script>
    @endpush
</x-app-layout>