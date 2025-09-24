<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="max-w-xl">
        <h2 class="text-lg font-medium text-gray-900">
            Thành Tích
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Điểm kinh nghiệm và các huy hiệu bạn đã đạt được.
        </p>

        <div class="mt-6">
            <p class="text-sm font-medium text-gray-700">Điểm kinh nghiệm: 
                <span class="text-lg font-bold text-indigo-600">{{ auth()->user()->experience_points }}</span>
            </p>
        </div>
        
        <div class="mt-4">
            <p class="text-sm font-medium text-gray-700">Huy hiệu:</p>
            <div class="mt-2 flex flex-wrap gap-4">
                @forelse (auth()->user()->badges as $badge)
                    <div class="flex items-center space-x-2" title="{{ $badge->description }}">
                        {{-- Giả sử bạn có file icon tại public/badges/ --}}
                        {{-- <img src="{{ asset($badge->icon_path) }}" class="h-10 w-10"> --}}
                        <span class="px-2 py-1 text-sm font-semibold rounded-full bg-yellow-200 text-yellow-800">{{ $badge->name }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Bạn chưa có huy hiệu nào. Hãy mượn sách để bắt đầu!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="max-w-xl">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Sự Kiện Đã Đăng Ký') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Danh sách các sự kiện bạn đã đăng ký tham gia.') }}
        </p>

        <div class="mt-6 space-y-4">
            @forelse ($user->registeredEvents as $event)
                <div class="border-l-4 pl-4 {{ $event->start_time >= now() ? 'border-blue-500' : 'border-gray-300' }}">
                    <a href="{{ route('events.show', $event) }}" class="font-semibold text-gray-800 hover:text-indigo-600">
                        {{ $event->title }}
                    </a>
                    <p class="text-sm text-gray-600">
                        {{ $event->start_time->format('H:i, d/m/Y') }} tại {{ $event->location }}
                    </p>
                    @if ($event->start_time < now())
                        <span class="text-xs text-gray-500 font-medium">Đã diễn ra</span>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">
                    {{ __('Bạn chưa đăng ký sự kiện nào. Hãy khám phá các sự kiện của thư viện!') }}
                </p>
            @endforelse
        </div>
    </div>
</div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
