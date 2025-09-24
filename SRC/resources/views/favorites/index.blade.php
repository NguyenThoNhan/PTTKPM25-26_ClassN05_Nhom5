<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tủ Sách Của Tôi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse ($favoriteBooks as $book)
                            <a href="{{ route('books.show', $book) }}" class="block group">
                                <div class="overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out bg-white h-full flex flex-col">
                                    <div class="flex-shrink-0">
                                        <img class="h-56 w-full object-cover" src="{{ $book->cover_url }}" alt="Bìa sách {{ $book->title }}">
                                    </div>
                                    <div class="p-4 flex flex-col flex-grow">
                                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">{{ Str::limit($book->title, 40) }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $book->author }}</p>
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach($book->categories as $category)
                                                <span class="text-xs bg-gray-200 text-gray-800 px-2 py-1 rounded-full">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full text-center py-20">
                                <i class="fa-regular fa-folder-open fa-4x text-gray-300 mb-4"></i>
                                <p class="text-gray-500 text-lg font-semibold">Tủ sách của bạn đang trống.</p>
                                <p class="text-gray-400 mt-1">Hãy tìm những cuốn sách bạn yêu thích và bấm vào biểu tượng trái tim nhé!</p>
                                <a href="{{ route('home') }}" class="mt-4 inline-block bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700">Khám Phá Ngay</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Phân trang -->
                    <div class="mt-8">
                        {{ $favoriteBooks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>