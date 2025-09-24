@if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
        <strong class="font-bold">Có lỗi xảy ra!</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Tiêu đề sự kiện -->
<div class="mb-4">
    <x-input-label for="title" :value="__('Tiêu đề sự kiện')" />
    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event->title ?? '')" required />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<!-- Mô tả -->
<div class="mb-4">
    <x-input-label for="description" :value="__('Mô tả chi tiết')" />
    <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $event->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<!-- Địa điểm -->
<div class="mb-4">
    <x-input-label for="location" :value="__('Địa điểm')" />
    <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $event->location ?? '')" required />
    <x-input-error :messages="$errors->get('location')" class="mt-2" />
</div>

<!-- Thời gian -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div>
        <x-input-label for="start_time" :value="__('Thời gian bắt đầu')" />
        <x-text-input id="start_time" class="block mt-1 w-full" type="datetime-local" name="start_time" :value="old('start_time', isset($event->start_time) ? $event->start_time->format('Y-m-d\TH:i') : '')" required />
        <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="end_time" :value="__('Thời gian kết thúc')" />
        <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time" :value="old('end_time', isset($event->end_time) ? $event->end_time->format('Y-m-d\TH:i') : '')" required />
        <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
    </div>
</div>

<!-- Số người tham gia tối đa -->
<div class="mb-4">
    <x-input-label for="max_attendees" :value="__('Số người tham gia tối đa (để trống nếu không giới hạn)')" />
    <x-text-input id="max_attendees" class="block mt-1 w-full" type="number" name="max_attendees" :value="old('max_attendees', $event->max_attendees ?? '')" min="1" />
    <x-input-error :messages="$errors->get('max_attendees')" class="mt-2" />
</div>

<!-- Nút Submit -->
<div class="flex items-center justify-end mt-6 border-t pt-4">
    <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Hủy</a>
    <x-primary-button>
        {{ isset($event) ? 'Cập Nhật Sự Kiện' : 'Tạo Sự Kiện' }}
    </x-primary-button>
</div>