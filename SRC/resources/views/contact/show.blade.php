<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liên Hệ & Hỏi Đáp') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <h3 class="text-2xl font-bold">Gửi câu hỏi cho chúng tôi</h3>
                    <p class="mt-2 text-gray-600">Nếu bạn có bất kỳ thắc mắc hoặc góp ý nào, đừng ngần ngại điền vào form bên dưới. Chúng tôi sẽ trả lời bạn sớm nhất có thể.</p>

                    @if (session('success'))
                        <div class="mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}" class="mt-6 space-y-6">
                        @csrf
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Tên của bạn')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', auth()->user()->name ?? '')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Địa chỉ Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', auth()->user()->email ?? '')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- Message -->
                        <div>
                             <x-input-label for="message" :value="__('Nội dung câu hỏi/góp ý')" />
                             <textarea id="message" name="message" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('message') }}</textarea>
                             <x-input-error class="mt-2" :messages="$errors->get('message')" />
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Gửi Tin Nhắn') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>