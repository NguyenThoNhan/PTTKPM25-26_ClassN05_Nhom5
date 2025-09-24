<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sự Kiện Sắp Diễn Ra') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($events as $event)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                        <div class="p-6 flex-grow">
                            <p class="text-sm text-indigo-600 font-semibold">
                                {{ $event->start_time->format('d/m/Y, H:i') }}
                            </p>
                            <a href="{{ route('events.show', $event) }}">
                                <h3 class="mt-2 text-xl font-bold text-gray-900 hover:text-indigo-700">{{ $event->title }}</h3>
                            </a>
                            <p class="mt-2 text-sm text-gray-600">
                                <i class="fa-solid fa-location-dot mr-1"></i> {{ $event->location }}
                            </p>
                            <p class="mt-3 text-base text-gray-600 line-clamp-3">
                                {{ $event->description }}
                            </p>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">
                                    <i class="fa-solid fa-users mr-1"></i>
                                    {{ $event->attendees_count }} / {{ $event->max_attendees ?? '∞' }}
                                </span>
                                <a href="{{ route('events.show', $event) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                    Xem chi tiết →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow-lg p-12 text-center">
                        <p class="text-gray-500 text-lg">Hiện tại chưa có sự kiện nào sắp diễn ra.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>