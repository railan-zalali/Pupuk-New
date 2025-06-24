<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Detail Produk') }}</h2>

            <div class="mt-4 sm:mt-0 flex space-x-2">
                <a href="{{ route('products.edit', $product) }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Produk
                </a>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('products.duplicate', $product) }}" class="inline-flex items-center rounded-md bg-blue-100 dark:bg-blue-700 px-3 py-2 text-sm font-medium text-blue-700 dark:text-blue-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                    Duplikasi Produk
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-50 dark:bg-green-900/50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400 dark:text-green-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Product Details -->
        <div
            class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Image -->
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Gambar Produk</h3>
                        <div class="aspect-w-1 aspect-h-1 rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                    class="object-cover w-full h-full">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="h-12 w-12 text-gray-400 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Informasi Produk</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Kode</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product->code }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Nama</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Kategori</dt>
                                <dd>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        {{ $product->category->name }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Stock & Price -->
                    <div
                        class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Stok & Harga</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Stok</dt>
                                <dd
                                    class="@if ($product->stock <= $product->min_stock) text-red-600 dark:text-red-400 @else text-green-600 dark:text-green-400 @endif font-medium">
                                    {{ $product->stock }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Minimal Stok</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $product->min_stock }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Harga Beli</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Harga Jual</dt>
                                <dd class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Form Penyesuaian Stok -->
                <div
                    class="mt-6 bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Penyesuaian Stok
                    </h3>

                    <form action="{{ route('products.updateStock', $product) }}" method="POST"
                        class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                            <div>
                                <x-input-label for="adjustment_type" value="Jenis Penyesuaian"
                                    class="font-medium text-gray-700 dark:text-gray-300" />
                                <select id="adjustment_type" name="adjustment_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-700 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300"
                                    required>
                                    <option value="add">Tambah Stok</option>
                                    <option value="subtract">Kurangi Stok</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="quantity" value="Jumlah"
                                    class="font-medium text-gray-700 dark:text-gray-300" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <x-text-input id="quantity" name="quantity" type="number"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-700 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300"
                                        min="1" placeholder="0" required />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">unit</span>
                                    </div>
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <x-input-label for="notes" value="Catatan"
                                    class="font-medium text-gray-700 dark:text-gray-300" />
                                <x-text-input id="notes" name="notes" type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-700 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300"
                                    placeholder="Catatan opsional" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-800 dark:active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition ease-in-out duration-150">
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
                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Riwayat Pergerakan Stok
                    </h3>

                    <div
                        class="mt-2 overflow-x-auto bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                        Jenis
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                        Jumlah
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                        Sebelum
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                        Sesudah
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                        Referensi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                        Catatan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @forelse ($product->stockMovements as $movement)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $movement->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($movement->type === 'in')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                                                    <svg class="mr-1 h-2 w-2 text-green-400 dark:text-green-300"
                                                        fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Masuk
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300">
                                                    <svg class="mr-1 h-2 w-2 text-red-400 dark:text-red-300"
                                                        fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Keluar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $movement->quantity }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $movement->before_stock }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $movement->after_stock }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 capitalize">
                                            {{ $movement->reference_type }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $movement->notes ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                            <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-600 mb-2"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Belum ada
                                                pergerakan stok
                                            </p>
                                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Riwayat pergerakan
                                                stok akan
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
</x-app-layout>
