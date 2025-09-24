<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <i class="fas fa-qrcode text-blue-500 text-2xl"></i>
                <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    QR Code Scanner
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Quét QR Code để mượn sách</h3>
                    <p class="text-gray-600">Hướng camera về phía QR code trên sách để quét và mượn nhanh</p>
                </div>

                <!-- QR Scanner -->
                <div class="bg-gray-100 rounded-xl p-8 mb-8">
                    <div id="qr-scanner" class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-camera text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">Camera sẽ được kích hoạt khi bạn cho phép</p>
                        </div>
                    </div>
                </div>

                <!-- Manual QR Input -->
                <div class="bg-blue-50 rounded-xl p-6 mb-8">
                    <h4 class="font-semibold text-blue-800 mb-4">
                        <i class="fas fa-keyboard mr-2"></i>
                        Hoặc nhập QR code thủ công
                    </h4>
                    <div class="flex space-x-4">
                        <input type="text" 
                               id="manual-qr-input" 
                               placeholder="Nhập QR code data..."
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button onclick="scanManualQR()" 
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-search mr-2"></i>
                            Quét
                        </button>
                    </div>
                </div>

                <!-- Scan Result -->
                <div id="scan-result" class="hidden">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                        <h4 class="font-semibold text-green-800 mb-4">
                            <i class="fas fa-check-circle mr-2"></i>
                            Kết quả quét QR
                        </h4>
                        <div id="book-info" class="space-y-3"></div>
                        <div class="mt-6 flex space-x-4">
                            <button id="borrow-btn" 
                                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-book mr-2"></i>
                                Mượn sách
                            </button>
                            <button id="view-btn" 
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="error-message" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <h4 class="font-semibold text-red-800 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Lỗi quét QR
                        </h4>
                        <p id="error-text" class="text-red-600"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentBook = null;

        // Manual QR scan
        function scanManualQR() {
            const qrData = document.getElementById('manual-qr-input').value;
            if (!qrData) {
                showError('Vui lòng nhập QR code data');
                return;
            }
            processQRData(qrData);
        }

        // Process QR data
        function processQRData(qrData) {
            fetch('/qr-scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ qr_data: qrData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showBookInfo(data.book);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                showError('Lỗi kết nối: ' + error.message);
            });
        }

        // Show book info
        function showBookInfo(book) {
            currentBook = book;
            document.getElementById('scan-result').classList.remove('hidden');
            document.getElementById('error-message').classList.add('hidden');
            
            const bookInfo = document.getElementById('book-info');
            bookInfo.innerHTML = `
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-xl font-bold">
                        ${book.title.charAt(0)}
                    </div>
                    <div>
                        <h5 class="font-semibold text-gray-800">${book.title}</h5>
                        <p class="text-gray-600">Tác giả: ${book.author}</p>
                        <p class="text-sm text-gray-500">Loại: ${book.type === 'online' ? 'Sách online' : 'Sách vật lý'}</p>
                        ${book.type === 'physical' ? `<p class="text-sm text-gray-500">Số lượng: ${book.quantity}</p>` : ''}
                    </div>
                </div>
            `;
            
            // Update buttons
            document.getElementById('view-btn').onclick = () => {
                window.location.href = book.url;
            };
        }

        // Show error
        function showError(message) {
            document.getElementById('error-message').classList.remove('hidden');
            document.getElementById('scan-result').classList.add('hidden');
            document.getElementById('error-text').textContent = message;
        }

        // Borrow book
        document.getElementById('borrow-btn').onclick = function() {
            if (!currentBook) return;
            
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
            
            fetch(`/books/${currentBook.id}/qr-borrow`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Mượn sách thành công! Hạn trả: ' + data.loan.due_date);
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                alert('Lỗi kết nối: ' + error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-book mr-2"></i>Mượn sách';
            });
        };

        // Simulate camera access (for demo)
        document.getElementById('qr-scanner').onclick = function() {
            alert('Trong ứng dụng thực tế, đây sẽ mở camera để quét QR code. Hiện tại bạn có thể sử dụng chức năng nhập thủ công.');
        };
    </script>
    @endpush
</x-app-layout>
