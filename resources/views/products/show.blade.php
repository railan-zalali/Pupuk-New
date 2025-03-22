<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Detail Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
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

            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="mb-6 overflow-hidden bg-white shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header Informasi Produk -->
                    <div
                        class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 pb-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $product->name }}</h2>
                            <p class="text-sm text-gray-500">Kode: {{ $product->code }}</p>
                        </div>

                        <div class="mt-4 sm:mt-0 flex space-x-2">
                            <a href="{{ route('products.edit', $product) }}"
                                class="inline-flex items-center rounded-md bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Produk
                            </a>
                            <button onclick="window.history.back()"
                                class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </button>
                        </div>
                    </div>

                    <!-- Informasi Produk -->
                    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Column for Product Image -->
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Gambar Produk
                            </h3>
                            <div
                                class="mt-2 bg-gray-50 rounded-lg p-2 border border-gray-100 flex items-center justify-center shadow-sm">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                        class="max-h-64 rounded-md object-contain" />
                                @else
                                    <div class="py-8 px-4 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">Tidak ada gambar</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Column for Product Info -->
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Informasi Produk
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 shadow-sm">
                                <dl class="space-y-4">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Kode</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $product->code }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $product->name }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                        <dd class="text-sm font-medium">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $product->category->name }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Deskripsi</dt>
                                        <dd
                                            class="text-sm text-gray-800 bg-white p-2 rounded-md border border-gray-200">
                                            {{ $product->description ?? 'Tidak ada deskripsi' }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Column for Stock & Price Info -->
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Stok & Harga
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 shadow-sm">
                                <dl class="space-y-4">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Stok Saat Ini</dt>
                                        <dd
                                            class="@if ($product->stock <= $product->min_stock) text-red-600 font-bold @else text-green-600 font-bold @endif">
                                            {{ $product->stock }}
                                            <span class="text-xs text-gray-500 font-normal">unit</span>
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Stok Minimum</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $product->min_stock }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Status Stok</dt>
                                        <dd>
                                            @if ($product->stock <= $product->min_stock)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Stok Menipis
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Stok Cukup
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="pt-2 border-t border-gray-200">
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Harga Beli</dt>
                                        <dd class="text-base font-medium text-gray-900">Rp
                                            {{ number_format($product->purchase_price, 0, ',', '.') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Harga Jual</dt>
                                        <dd class="text-xl font-bold text-indigo-700">Rp
                                            {{ number_format($product->selling_price, 0, ',', '.') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Form Penyesuaian Stok -->
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-2"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Penyesuaian Stok
                        </h3>

                        <form action="{{ route('products.updateStock', $product) }}" method="POST"
                            class="bg-gray-50 p-4 rounded-lg border border-gray-100 shadow-sm">
                            @csrf
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                                <div>
                                    <x-input-label for="adjustment_type" value="Jenis Penyesuaian"
                                        class="font-medium text-gray-700" />
                                    <select id="adjustment_type" name="adjustment_type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required>
                                        <option value="add">Tambah Stok</option>
                                        <option value="subtract">Kurangi Stok</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="quantity" value="Jumlah" class="font-medium text-gray-700" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <x-text-input id="quantity" name="quantity" type="number"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            min="1" placeholder="0" required />
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">unit</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <x-input-label for="notes" value="Catatan"
                                        class="font-medium text-gray-700" />
                                    <x-text-input id="notes" name="notes" type="text"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        placeholder="Catatan opsional" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Sesuaikan Stok
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Riwayat Pergerakan Stok -->
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-2"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Riwayat Pergerakan Stok
                        </h3>

                        <div class="mt-2 overflow-x-auto bg-white rounded-lg border border-gray-200 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Tanggal
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Jenis
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Jumlah
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Sebelum
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Sesudah
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Referensi
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                            Catatan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($product->stockMovements as $movement)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                                {{ $movement->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($movement->type === 'in')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="mr-1 h-2 w-2 text-green-400" fill="currentColor"
                                                            viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Masuk
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg class="mr-1 h-2 w-2 text-red-400" fill="currentColor"
                                                            viewBox="0 0 8 8">
                                                            <circle cx="4" cy="4" r="3" />
                                                        </svg>
                                                        Keluar
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $movement->quantity }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $movement->before_stock }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $movement->after_stock }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 capitalize">
                                                {{ $movement->reference_type }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $movement->notes ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                                <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                <p class="text-gray-500 text-lg font-medium">Belum ada pergerakan stok
                                                </p>
                                                <p class="text-gray-400 text-sm mt-1">Riwayat pergerakan stok akan
                                                    muncul di sini</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
