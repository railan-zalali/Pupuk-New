<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Detail Pelanggan') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">Informasi lengkap dan riwayat transaksi pelanggan</p>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('sales.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Buat Transaksi
                </a>

                <a href="{{ route('customers.edit', $customer) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Pelanggan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Profile Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="border-b border-gray-200">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center">
                            <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                                <div
                                    class="bg-indigo-100 text-indigo-800 flex items-center justify-center w-24 h-24 rounded-full">
                                    <span class="text-3xl font-bold">{{ substr($customer->nama, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $customer->nama }}</h2>
                                <p class="text-sm text-gray-500">NIK: {{ $customer->nik }}</p>
                                <div class="mt-2 flex flex-wrap items-center text-sm text-gray-700">
                                    <div class="mr-6 mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $customer->desa_nama }}, {{ $customer->kecamatan_nama }}
                                    </div>
                                    <div class="mr-6 mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $customer->kabupaten_nama }}
                                    </div>
                                    <div class="mt-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                        {{ $customer->provinsi_nama }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0 mt-4 md:mt-0 md:ml-6 flex space-x-3">
                                <div
                                    class="flex flex-col items-center justify-center bg-blue-50 text-blue-700 rounded-lg px-4 py-2">
                                    <span class="text-2xl font-bold">{{ $customer->sales->count() }}</span>
                                    <span class="text-xs uppercase tracking-wide">Transaksi</span>
                                </div>
                                <div
                                    class="flex flex-col items-center justify-center bg-green-50 text-green-700 rounded-lg px-4 py-2">
                                    <span class="text-lg font-bold">Rp
                                        {{ number_format($customer->sales->sum('total_amount'), 0, ',', '.') }}</span>
                                    <span class="text-xs uppercase tracking-wide">Total Belanja</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                    <!-- Detail Informasi -->
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Detail
                        </h3>

                        <div class="mt-4 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-3 rounded-md">
                                    <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                                    <p class="mt-1 text-md font-semibold text-gray-900">{{ $customer->nama }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-3 rounded-md">
                                <p class="text-sm font-medium text-gray-500">Alamat Lengkap</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $customer->alamat ?? '-' }}</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-3 rounded-md">
                                    <p class="text-sm font-medium text-gray-500">Desa</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $customer->desa_nama }}</p>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-md">
                                    <p class="text-sm font-medium text-gray-500">Kecamatan</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $customer->kecamatan_nama }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-3 rounded-md">
                                    <p class="text-sm font-medium text-gray-500">Kabupaten</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $customer->kabupaten_nama }}</p>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-md">
                                    <p class="text-sm font-medium text-gray-500">Provinsi</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $customer->provinsi_nama }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Penjualan -->
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Ringkasan Penjualan
                        </h3>

                        <div class="mt-4 grid grid-cols-1 gap-4">
                            <div class="bg-white border border-indigo-100 rounded-lg shadow-sm p-5">
                                <div class="flex items-center mb-3">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="ml-3 text-lg font-medium text-gray-900">Total Penjualan</h3>
                                </div>
                                <div class="text-3xl font-bold text-indigo-700">
                                    {{ $customer->sales->count() }}
                                    <span class="text-sm font-normal text-gray-500">transaksi</span>
                                </div>
                            </div>

                            <div class="bg-white border border-green-100 rounded-lg shadow-sm p-5">
                                <div class="flex items-center mb-3">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="ml-3 text-lg font-medium text-gray-900">Jumlah Total</h3>
                                </div>
                                <div class="text-2xl font-bold text-green-700">
                                    Rp {{ number_format($customer->sales->sum('total_amount'), 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="bg-white border border-blue-100 rounded-lg shadow-sm p-5">
                                <div class="flex items-center mb-3">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="ml-3 text-lg font-medium text-gray-900">Transaksi Terakhir</h3>
                                </div>
                                <div class="text-lg font-medium text-blue-700">
                                    @if ($customer->sales->count() > 0)
                                        {{ $customer->sales->sortByDesc('created_at')->first()->created_at->format('d/m/Y') }}
                                    @else
                                        <span class="text-gray-500">Belum ada transaksi</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Penjualan Terbaru -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Riwayat Transaksi
                        </h3>

                        <a href="{{ route('sales.create') }}"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Buat Transaksi Baru
                        </a>
                    </div>

                    <div class="overflow-x-auto relative">
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Faktur</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Jumlah</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($customer->sales as $sale)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                {{ $sale->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                                <span class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 mr-1.5 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    {{ $sale->invoice_number }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                @if ($sale->trashed())
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg class="mr-1 h-2 w-2 text-red-400" fill="currentColor"
                                                            viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Batal
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="mr-1 h-2 w-2 text-green-400" fill="currentColor"
                                                            viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Selesai
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                                <a href="{{ route('sales.show', $sale) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-10 w-10 text-gray-400 mb-2" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                    </svg>
                                                    <p class="text-gray-500 text-lg font-medium">Belum ada transaksi
                                                    </p>
                                                    <p class="text-gray-400 text-sm mt-1">Pelanggan ini belum melakukan
                                                        transaksi</p>
                                                    <a href="{{ route('sales.create') }}"
                                                        class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg>
                                                        Buat Transaksi Baru
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
