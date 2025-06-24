<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Stok Harian') }}</h2>
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('weekly.stock.details') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Lihat Stok Mingguan') }}
                </a>
            </div>
        </div>

        <!-- Outgoing Stock Section -->
        <div>
            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Stok Keluar Harian</h3>
            <div class="mt-2">
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @foreach ($outgoingStockDetails as $movement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="whitespace-nowrap px-6 py-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $movement->product->name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $movement->quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $movement->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Incoming Stock Section -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">Stok Masuk Harian</h3>
            <div class="mt-2">
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @foreach ($incomingStockDetails as $movement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="whitespace-nowrap px-6 py-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $movement->product->name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $movement->quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $movement->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
