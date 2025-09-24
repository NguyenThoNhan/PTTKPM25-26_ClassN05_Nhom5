<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <!-- Thông báo Success/Error -->
                     @if (session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
                    
                    <div class="mt-4 text-gray-600 space-y-2">
                        <p><strong class="font-semibold">Thời gian:</strong> {{ $event->start_time->format('H:i, d/m/Y') }} - {{ $event->end_time->format('H:i, d/m/Y') }}</p>
                        <p><strong class="font-semibold">Địa điểm:</strong> {{ $event->location }}</p>
                        <p><strong class="font-semibold">Số người tham gia:</strong> {{ $event->attendees()->count() }} / {{ $event->max_attendees ?? 'Không giới hạn' }}</p>
                    </div>

                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h2 class="text-xl font-semibold text-gray-800">Mô tả sự kiện</h2>
                        <p class="mt-2 text-gray-700 leading-relaxed">{!! nl2br(e($event->description)) !!}</p>
                    </div>

                    <!-- Nút hành động -->
                    <div class="mt-8">
                        @auth
                            @if ($isRegistered)
                                <!-- Nếu đã đăng ký, hiển thị nút Hủy -->
                                <form action="{{ route('events.unregister', $event) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đăng ký sự kiện này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700">Hủy Đăng Ký</button>
                                </form>
                                <p class="text-center mt-2 text-green-600 font-semibold">Bạn đã đăng ký sự kiện này.</p>
                            @else
                                <!-- Nếu chưa đăng ký -->
                                @if ($remainingSlots == 0)
                                    <button disabled class="w-full text-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">Đã Hết Chỗ</button>
                                @elseif ($event->start_time < now())
                                    <button disabled class="w-full text-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">Sự Kiện Đã Diễn Ra</button>
                                @else
                                    <form action="{{ route('events.register', $event) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Xác Nhận Đăng Ký</button>
                                    </form>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Đăng nhập để đăng ký
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>