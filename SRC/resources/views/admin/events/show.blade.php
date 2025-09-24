<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chi Tiết Sự Kiện
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <h3 class="text-2xl font-bold text-gray-800">{{ $event->title }}</h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <span>từ {{ $event->start_time->format('H:i, d/m/Y') }}</span>
                        <span>đến {{ $event->end_time->format('H:i, d/m/Y') }}</span>
                    </div>
                    <p class="mt-2 font-semibold text-gray-700">Tại: {{ $event->location }}</p>

                    <div class="mt-6 border-t pt-4 prose max-w-none text-gray-700">
                        {!! nl2br(e($event->description)) !!}
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <h4 class="text-lg font-semibold">Danh sách người tham gia ({{ $event->attendees->count() }} / {{ $event->max_attendees ?? 'Không giới hạn' }})</h4>
                         <ul class="divide-y divide-gray-200 mt-2">
                            @forelse ($event->attendees as $attendee)
                                <li class="py-3 flex justify-between items-center">
                                    <span>{{ $attendee->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $attendee->email }}</span>
                                </li>
                            @empty
                                <li class="py-3 text-gray-500">Chưa có ai đăng ký tham gia sự kiện này.</li>
                            @endforelse
                        </ul>
                    </div>

                     <div class="mt-6 flex justify-end">
                        <a href="{{ route('admin.events.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                            ← Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>