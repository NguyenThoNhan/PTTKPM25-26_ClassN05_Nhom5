<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#667eea">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="BookHaven">
        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/favicon.ico">

        <title>{{ config('app.name', 'BookHaven') }} - Thư Viện Số</title>
        <meta name="description" content="Hệ thống quản lý thư viện hiện đại với chữ ký số, gamification và trải nghiệm người dùng tuyệt vời">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Font Awesome & Swiper CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <!-- Vite Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Global Styles -->
        <style>
            :root {
                --primary-color: #4f46e5;
                --secondary-color: #7c3aed;
                --accent-color: #06b6d4;
                --success-color: #10b981;
                --warning-color: #f59e0b;
                --error-color: #ef4444;
            }
            
            * {
                scroll-behavior: smooth;
            }
            
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                min-height: 100vh;
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
                background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
                border-radius: 4px;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(45deg, #3730a3, #6d28d9);
            }
            
            /* Loading animation */
            .loading {
                animation: pulse 2s infinite;
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }
            
            /* Gradient text */
            .gradient-text {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading Nâng Cấp -->
            @if (isset($header))
                <header class="bg-white/80 backdrop-blur-md shadow-lg border-b border-gray-200/50 sticky top-0 z-40">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="relative">
                {{ $slot }}
            </main>
            
            <!-- Footer -->
            <footer class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-12 mt-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="col-span-1 md:col-span-2">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book text-white text-lg"></i>
                                </div>
                                <h3 class="text-2xl font-bold gradient-text">BookHaven</h3>
                            </div>
                            <p class="text-gray-300 mb-4 max-w-md">
                                Hệ thống quản lý thư viện hiện đại với chữ ký số, gamification và trải nghiệm người dùng tuyệt vời.
                            </p>
                            <div class="flex space-x-4">
                                <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-indigo-600 transition-colors duration-300">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-indigo-600 transition-colors duration-300">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-indigo-600 transition-colors duration-300">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Liên Kết Nhanh</h4>
                            <ul class="space-y-2">
                                <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Trang Chủ</a></li>
                                <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Thư Viện</a></li>
                                <li><a href="{{ route('events.index') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Sự Kiện</a></li>
                                <li><a href="{{ route('about.index') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Giới Thiệu</a></li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="text-lg font-semibold mb-4">Hỗ Trợ</h4>
                            <ul class="space-y-2">
                                <li><a href="{{ route('contact.show') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Liên Hệ</a></li>
                                <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Hướng Dẫn</a></li>
                                <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">FAQ</a></li>
                                <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Báo Lỗi</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-700 mt-6 pt-6 text-center">
                        <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} BookHaven — Thư viện số hiện đại.</p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>