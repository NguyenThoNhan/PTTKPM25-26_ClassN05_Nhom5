<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chỉnh Sửa Tài Liệu: {{ $loan->book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                     @if (session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <p class="text-sm text-gray-600 mb-4">Bạn có thể sửa đổi nội dung của tài liệu dưới đây. Khi bạn trả lại, hệ thống sẽ dùng chữ ký số để kiểm tra xem nội dung có còn vẹn toàn so với bản gốc hay không.</p>
                    
                    <form method="POST" action="{{ route('documents.update', $loan) }}">
                        @csrf
                        @method('PATCH')
                        <div>
                            <x-input-label for="content" :value="__('Nội dung tài liệu')" />
                            <textarea id="content" name="content" rows="20" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono">{{ old('content', $loan->book->content) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Lưu Thay Đổi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>