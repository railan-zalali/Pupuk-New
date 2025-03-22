<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                {{-- Kartu Laporan Penjualan --}}
                <div class="overflow-hidden rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Laporan Penjualan</h3>
                                <p class="mt-1 text-sm text-gray-500">Lihat laporan transaksi penjualan secara detail
                                </p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('reports.sales') }}"
                                class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                                Lihat Laporan
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Kartu Laporan Stok --}}
                <div class="overflow-hidden rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Laporan Stok</h3>
                                <p class="mt-1 text-sm text-gray-500">Pantau status inventaris Anda</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('reports.stock') }}"
                                class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                                Lihat Laporan
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Kartu Laporan Laba/Rugi --}}
                <div class="overflow-hidden rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Laporan Laba/Rugi</h3>
                                <p class="mt-1 text-sm text-gray-500">Tinjau kinerja keuangan</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('reports.profit-loss') }}"
                                class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-500">
                                Lihat Laporan
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
