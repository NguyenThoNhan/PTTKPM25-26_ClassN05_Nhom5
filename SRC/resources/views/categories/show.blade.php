<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Danh mục: <span class="text-indigo-600">{{ $category->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse ($books as $book)
                            <a href="{{ route('books.show', $book) }}" class="block group">
                                <div class="overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out bg-white h-full flex flex-col">
                                    <div class="flex-shrink-0">
                                        <img class="h-56 w-full object-cover" src="{{ $book->cover_url }}" alt="Bìa sách {{ $book->title }}">
                                    </div>
                                    <div class="p-4 flex flex-col flex-grow">
                                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">{{ Str::limit($book->title, 40) }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $book->author }}</p>
                                        <div class="mt-auto pt-2">
                                            @if($book->type == 'online')
                                                <span class="inline-block bg-green-200 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full">Tài liệu Online</span>
                                            @else
                                                <span class="inline-block bg-blue-200 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded-full">Sách Vật Lý</span>
                                                <span class="text-sm {{ $book->quantity > 0 ? 'text-gray-700' : 'text-red-500 font-bold' }}">
                                                    {{ $book->quantity > 0 ? 'Còn lại: ' . $book->quantity : 'Hết sách' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500 text-lg">Chưa có sách nào trong danh mục này.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>