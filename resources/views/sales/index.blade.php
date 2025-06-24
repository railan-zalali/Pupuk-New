<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Transaksi Penjualan') }}</h2>

            <!-- Optional action buttons -->
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <div class="relative text-gray-700 focus-within:text-gray-800">
                    <input type="text" id="searchInput" placeholder="Cari transaksi..."
                        class="input-primary pl-10 pr-4 py-2 rounded-md border dark:bg-gray-800 dark:text-gray-300 border-gray-300 focus:ring focus:ring-blue-200 focus:outline-none"
                        autofocus>
                    <i class="ti ti-search absolute left-3 top-2.5 text-gray-400 dark:text-gray-300"></i>
                </div>
                <a href="{{ route('sales.create') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Transaksi
                </a>
            </div>
        </div>

        <div class="p-6 text-gray-900">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div
                class="overflow-x-auto relative bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-md">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Tanggal</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Faktur</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Pelanggan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Total</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Pembayaran</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse ($sales as $sale)
                            <tr class="hover:bg-gray-50 text-xs dark:hover:bg-gray-700">
                                <td class="whitespace-nowrap px-6 py-4 text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }} <br>
                                    <span class="text-gray-500 dark:text-gray-400 text-xs font-normal">
                                        {{ \Carbon\Carbon::parse($sale->date)->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $sale->invoice_number }}</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                    {{ $sale->customer->nama ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $sale->payment_method === 'cash' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                        {{ ucfirst($sale->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($sale->trashed())
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Batal
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('sales.show', $sale) }}"
                                            class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @unless ($sale->trashed())
                                            <form action="{{ route('sales.destroy', $sale) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200 rounded-full hover:bg-red-50 dark:hover:bg-red-900/50"
                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada
                                            transaksi
                                            ditemukan</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Tambahkan transaksi
                                            baru
                                            untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $sales->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                let debounceTimer;

                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        // Implementasi pencarian AJAX di sini
                    }, 300);
                });
            });
        </script>
    @endpush
</x-app-layout>
