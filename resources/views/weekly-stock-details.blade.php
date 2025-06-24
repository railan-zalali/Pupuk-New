<x-app-layout>
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                {{ __('Detail Stok Mingguan') }}
            </h2>
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <a href="{{ route('stock.details') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        @forelse ($stockDetails as $detail)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">
                    {{ $detail['date']->format('l, d M Y') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Stok Keluar --}}
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-2">
                            Stok Keluar
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="p-2 text-left">Produk</th>
                                        <th class="p-2 text-left">Jumlah</th>
                                        <th class="p-2 text-left">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($detail['outgoing_stock'] as $movement)
                                        <tr class="border-b dark:border-gray-700">
                                            <td class="p-2">{{ $movement->product->name }}</td>
                                            <td class="p-2">{{ $movement->quantity }}</td>
                                            <td class="p-2">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-2 text-center text-gray-500">
                                                Tidak ada stok keluar
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Stok Masuk --}}
                    <div>
                        <h4 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-2">
                            Stok Masuk
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700">
                                        <th class="p-2 text-left">Produk</th>
                                        <th class="p-2 text-left">Jumlah</th>
                                        <th class="p-2 text-left">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($detail['incoming_stock'] as $movement)
                                        <tr class="border-b dark:border-gray-700">
                                            <td class="p-2">{{ $movement->product->name }}</td>
                                            <td class="p-2">{{ $movement->quantity }}</td>
                                            <td class="p-2">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-2 text-center text-gray-500">
                                                Tidak ada stok masuk
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-4">
                Tidak ada data stok mingguan
            </div>
        @endforelse
    </div>
</x-app-layout>
