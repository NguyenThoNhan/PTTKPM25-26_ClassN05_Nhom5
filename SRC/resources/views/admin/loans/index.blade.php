<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản Lý Lịch Sử Mượn') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Bộ lọc -->
                    <div class="mb-4 flex space-x-2">
                        <a href="{{ route('admin.loans.index') }}" class="px-4 py-2 text-sm font-medium {{ !request('filter') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }} border border-gray-300 rounded-md hover:bg-gray-50">Tất cả</a>
                        <a href="{{ route('admin.loans.index', ['filter' => 'borrowed']) }}" class="px-4 py-2 text-sm font-medium {{ request('filter') == 'borrowed' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700' }} border border-gray-300 rounded-md hover:bg-gray-50">Đang Mượn</a>
                        <a href="{{ route('admin.loans.index', ['filter' => 'failed']) }}" class="px-4 py-2 text-sm font-medium {{ request('filter') == 'failed' ? 'bg-red-600 text-white' : 'bg-white text-gray-700' }} border border-gray-300 rounded-md hover:bg-gray-50">Không Vẹn Toàn</a>
                        <a href="{{ route('admin.loans.index', ['filter' => 'passed']) }}" class="px-4 py-2 text-sm font-medium {{ request('filter') == 'passed' ? 'bg-green-600 text-white' : 'bg-white text-gray-700' }} border border-gray-300 rounded-md hover:bg-gray-50">Vẹn Toàn</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sách</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người Mượn</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày Mượn</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày Trả</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng Thái Toàn Vẹn</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($loans as $loan)
                                <tr>
                                    <td class="px-6 py-4">{{ $loan->book->title }}</td>
                                    <td class="px-6 py-4">{{ $loan->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $loan->loan_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $loan->return_date ? $loan->return_date->format('d/m/Y') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($loan->book->type == 'online' && $loan->status == 'returned')
                                            @if($loan->integrity_status == 'passed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Vẹn toàn
                                                </span>
                                            @elseif($loan->integrity_status == 'failed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Không Vẹn Toàn
                                                </span>
                                            @endif
                                        @elseif($loan->status == 'borrowed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Đang mượn
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4">Không có dữ liệu.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $loans->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>