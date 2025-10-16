<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Về Chúng Tôi - Thư Viện BookHaven') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 prose max-w-none">
                    
                    {{-- Phần tiêu đề với hiệu ứng gõ chữ --}}
                    <div class="text-center mb-12">
                        <h1 id="typewriter" class="text-4xl font-bold text-indigo-600"></h1>
                    </div>

                    {{-- Phần nội dung chính --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-justify">
                        <div class="md:col-span-1 text-center">
                            <i class="fa-solid fa-lightbulb fa-5x text-yellow-400 mb-4"></i>
                            <h3 class="font-semibold">Sứ Mệnh</h3>
                        </div>
                        <div class="md:col-span-2">
                            <p>Tại BookHaven, chúng tôi tin rằng tri thức là ngọn hải đăng soi sáng con đường phát triển của mỗi cá nhân và toàn xã hội. Sứ mệnh của chúng tôi là xây dựng một không gian số hóa, nơi mọi rào cản về địa lý và thời gian đều được xóa bỏ, mang nguồn tài nguyên tri thức vô tận đến với tất cả mọi người. Chúng tôi cam kết bảo tồn, phát triển và lan tỏa giá trị của văn hóa đọc trong kỷ nguyên số.</p>
                        </div>
                    </div>

                    <hr class="my-12">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-justify">
                        <div class="md:col-span-1 text-center">
                            <i class="fa-solid fa-eye fa-5x text-blue-500 mb-4"></i>
                            <h3 class="font-semibold">Tầm Nhìn</h3>
                        </div>
                        <div class="md:col-span-2">
                            <p>BookHaven hướng tới việc trở thành một hệ sinh thái thư viện số hàng đầu, một hình mẫu về việc ứng dụng công nghệ để nâng cao trải nghiệm người dùng. Chúng tôi không chỉ là một kho lưu trữ sách, mà còn là một cộng đồng tri thức sống động, nơi các độc giả, nhà nghiên cứu và tác giả có thể kết nối, chia sẻ và cùng nhau sáng tạo. Tầm nhìn của chúng tôi là một tương lai nơi mỗi người đều có thể dễ dàng tiếp cận và làm chủ kho tàng kiến thức của nhân loại.</p>
                        </div>
                    </div>

                    <hr class="my-12">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-justify">
                        <div class="md:col-span-1 text-center">
                            <i class="fa-solid fa-shield-halved fa-5x text-green-500 mb-4"></i>
                            <h3 class="font-semibold">Giá Trị Cốt Lõi</h3>
                        </div>
                         <div class="md:col-span-2">
                            <ul>
                                <li><strong>Toàn vẹn (Integrity):</strong> Chúng tôi áp dụng công nghệ chữ ký số để đảm bảo tính toàn vẹn và nguyên bản của mọi tài liệu số, mang lại sự tin cậy tuyệt đối cho người dùng.</li>
                                <li><strong>Kết nối (Connection):</strong> Tạo ra một môi trường tương tác, nơi các sự kiện, thảo luận và đánh giá sách giúp kết nối những người có cùng đam mê.</li>
                                <li><strong>Sáng tạo (Innovation):</strong> Luôn tiên phong trong việc áp dụng các công nghệ mới để cải tiến và làm phong phú thêm trải nghiệm của độc giả.</li>
                                <li><strong>Khai phóng (Enlightenment):</strong> Cung cấp công cụ và nguồn lực để mỗi người dùng có thể tự do khám phá, học hỏi và khai phóng tiềm năng của bản thân.</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    {{-- Phần Script cho hiệu ứng gõ chữ --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('typewriter');
            const text = "Khơi Nguồn Tri Thức, Kết Nối Tương Lai";
            let index = 0;
            let isDeleting = false;

            function type() {
                let currentText = text.substring(0, index);
                element.innerHTML = currentText + '<span class="animate-ping">|</span>'; // Thêm con trỏ nhấp nháy

                if (!isDeleting && index < text.length) {
                    index++;
                    setTimeout(type, 150); // Tốc độ gõ
                } else if (isDeleting && index > 0) {
                    // Hiện tại không có chức năng xóa, nếu muốn thêm thì code vào đây
                }
            }
            
            type();
        });
    </script>
    @endpush

</x-app-layout>