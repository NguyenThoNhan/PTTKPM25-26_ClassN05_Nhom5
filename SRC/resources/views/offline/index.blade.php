<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <i class="fas fa-download text-blue-500 text-2xl"></i>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Đọc Offline
                </h2>
            </div>
            <div class="flex items-center space-x-4">
                <div id="connection-status" class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Online</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- PWA Install Banner -->
            <div id="pwa-install-banner" class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-8 border border-blue-200 hidden">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-mobile-alt text-blue-500 text-2xl"></i>
                        <div>
                            <h3 class="font-semibold text-blue-800">Cài đặt BookHaven App</h3>
                            <p class="text-sm text-blue-600">Cài đặt để đọc offline và có trải nghiệm tốt hơn</p>
                        </div>
                    </div>
                    <button id="install-pwa" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Cài đặt
                    </button>
                </div>
            </div>

            <!-- Offline Books -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-book text-blue-500 mr-3"></i>
                        Sách đã tải xuống
                    </h3>
                    <button onclick="checkOfflineStatus()" class="text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fas fa-sync mr-2"></i>
                        Kiểm tra trạng thái
                    </button>
                </div>

                @if($offlineBooks->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($offlineBooks as $loan)
                            <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-start space-x-4 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold">
                                        {{ substr($loan->book->title, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800 mb-1">{{ $loan->book->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $loan->book->author }}</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Ngày mượn:</span>
                                        <span class="font-medium">{{ $loan->loan_date->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500">Hạn trả:</span>
                                        <span class="font-medium text-red-600">{{ $loan->due_date->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <button onclick="downloadBook({{ $loan->book->id }})" 
                                            class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                                        <i class="fas fa-download mr-2"></i>
                                        Tải xuống
                                    </button>
                                    <button onclick="readOffline({{ $loan->book->id }})" 
                                            class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm">
                                        <i class="fas fa-book-open mr-2"></i>
                                        Đọc
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-book-open text-gray-300 text-6xl mb-6"></i>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Chưa có sách nào được tải xuống</h3>
                        <p class="text-gray-600 mb-8">Mượn sách online và tải xuống để đọc offline</p>
                        <a href="{{ route('home') }}" 
                           class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-search mr-2"></i>
                            Tìm sách để mượn
                        </a>
                    </div>
                @endif
            </div>

            <!-- Offline Reader Modal -->
            <div id="offline-reader-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                        <div class="flex items-center justify-between p-6 border-b border-gray-200">
                            <h3 class="text-xl font-bold text-gray-800" id="reader-title">Đọc Offline</h3>
                            <button onclick="closeOfflineReader()" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                            <div id="offline-content" class="prose max-w-none">
                                <!-- Content will be loaded here -->
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-6 border-t border-gray-200">
                            <div class="flex items-center space-x-4">
                                <button onclick="syncOfflineChanges()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-sync mr-2"></i>
                                    Đồng bộ
                                </button>
                                <span class="text-sm text-gray-500" id="sync-status">Chưa có thay đổi</span>
                            </div>
                            <button onclick="closeOfflineReader()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentOfflineBook = null;
        let hasChanges = false;

        // Check offline status
        function checkOfflineStatus() {
            fetch('/offline/status')
                .then(response => response.json())
                .then(data => {
                    const statusEl = document.getElementById('connection-status');
                    if (data.offline) {
                        statusEl.innerHTML = '<div class="w-3 h-3 bg-red-500 rounded-full"></div><span class="text-sm text-gray-600">Offline</span>';
                    } else {
                        statusEl.innerHTML = '<div class="w-3 h-3 bg-green-500 rounded-full"></div><span class="text-sm text-gray-600">Online</span>';
                    }
                });
        }

        // Download book for offline reading
        function downloadBook(bookId) {
            fetch(`/books/${bookId}/download`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tải xuống thành công! Bạn có thể đọc offline.');
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                alert('Lỗi kết nối: ' + error.message);
            });
        }

        // Read offline book
        function readOffline(bookId) {
            fetch(`/books/${bookId}/offline-content`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentOfflineBook = data.content;
                        showOfflineReader(data.content);
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Lỗi kết nối: ' + error.message);
                });
        }

        // Show offline reader
        function showOfflineReader(content) {
            document.getElementById('reader-title').textContent = content.book.title;
            document.getElementById('offline-content').innerHTML = `
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">${content.book.title}</h1>
                    <p class="text-gray-600 mb-4">Tác giả: ${content.book.author}</p>
                    <p class="text-gray-600 mb-6">${content.book.description}</p>
                </div>
                <div class="prose max-w-none">
                    <pre class="whitespace-pre-wrap font-mono text-sm leading-relaxed">${content.book.content}</pre>
                </div>
            `;
            document.getElementById('offline-reader-modal').classList.remove('hidden');
        }

        // Close offline reader
        function closeOfflineReader() {
            document.getElementById('offline-reader-modal').classList.add('hidden');
            currentOfflineBook = null;
            hasChanges = false;
        }

        // Sync offline changes
        function syncOfflineChanges() {
            if (!currentOfflineBook || !hasChanges) {
                alert('Không có thay đổi để đồng bộ');
                return;
            }

            const content = document.getElementById('offline-content').textContent;
            const contentHash = btoa(content); // Simple hash for demo

            fetch(`/books/${currentOfflineBook.book.id}/sync`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    content: content,
                    offline_hash: contentHash
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đồng bộ thành công!');
                    hasChanges = false;
                    document.getElementById('sync-status').textContent = 'Đã đồng bộ';
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                alert('Lỗi kết nối: ' + error.message);
            });
        }

        // PWA Install
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            document.getElementById('pwa-install-banner').classList.remove('hidden');
        });

        document.getElementById('install-pwa').addEventListener('click', () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        document.getElementById('pwa-install-banner').classList.add('hidden');
                    }
                    deferredPrompt = null;
                });
            }
        });

        // Register Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('SW registered: ', registration);
                })
                .catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
        }

        // Check offline status on load
        checkOfflineStatus();
    </script>
    @endpush
</x-app-layout>
