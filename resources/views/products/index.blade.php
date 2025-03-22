<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Produk') }}</h2>

            <!-- Optional action buttons -->
            <div class="flex items-center gap-3">
                <div class="relative text-gray-700 focus-within:text-gray-800">
                    <input type="text" id="searchInput" placeholder="Search products..."
                        class="input-primary pl-10 pr-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-blue-200 focus:outline-none"
                        autofocus>
                    <i class="ti ti-search absolute left-3 top-2.5 text-gray-400 dark:text-gray-800"></i>
                </div>
                <a href="{{ route('products.create') }}"
                    class="inline-flex items-center p-2 mr-4 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 transition-colors rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Produk
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
                                Gambar
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Kode
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Nama
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Kategori
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Stok
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Harga
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div
                                        class="h-12 w-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden shadow-sm">
                                        @if ($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}"
                                                alt="{{ $product->name }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $product->code }}</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($product->stock <= $product->min_stock)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                            {{ $product->stock }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                            {{ $product->stock }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">Rp
                                    {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('products.show', $product) }}"
                                            class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 transition-colors rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('products.edit', $product) }}"
                                            class="p-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200 transition-colors rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200 transition-colors rounded-full hover:bg-red-50 dark:hover:bg-red-900/50"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
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
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada
                                            produk
                                            ditemukan.</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Tambahkan produk baru
                                            untuk
                                            memulai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const tbody = document.querySelector('tbody');
                let debounceTimer;

                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        const searchQuery = this.value;

                        if (searchQuery.length >= 2) {
                            fetch(`/search/products?query=${encodeURIComponent(searchQuery)}`)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Respon jaringan tidak ok');
                                    }
                                    return response.json();
                                })
                                .then(response => {
                                    const products = response.data;
                                    tbody.innerHTML = ''; // Hapus konten tabel saat ini

                                    if (products.length === 0) {
                                        tbody.innerHTML = `
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada produk ditemukan.
                                </td>
                            </tr>
                            `;
                                        return;
                                    }

                                    products.forEach(product => {
                                        const stockClass = product.stock <= product
                                            .min_stock ?
                                            'text-red-600 dark:text-red-400 font-bold' :
                                            'text-green-600 dark:text-green-400';

                                        // Image HTML
                                        let imageHtml =
                                            `
                                                <div class="flex-shrink-0 h-12 w-12 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">`;

                                        if (product.image_path) {
                                            imageHtml +=
                                                `<img src="/storage/${product.image_path}" alt="${product.name}" class="h-full w-full object-cover">`;
                                        } else {
                                            imageHtml += `<svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>`;
                                        }

                                        imageHtml += `</div>`;

                                        tbody.innerHTML += `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="whitespace-nowrap px-6 py-2">${imageHtml}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-900 dark:text-gray-100">${product.code}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-100">${product.name}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-100">${product.category.name}</td>
                            <td class="px-6 py-4">
                            <span class="${stockClass}">
                                ${product.stock}
                            </span>
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-100">Rp ${new Intl.NumberFormat('id-ID').format(product.selling_price)}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <a href="/products/${product.id}" 
                                    class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 transition-colors rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="/products/${product.id}/edit"
                                    class="p-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200 transition-colors rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button onclick="deleteProduct(${product.id})"
                                    class="p-2 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200 transition-colors rounded-full hover:bg-red-50 dark:hover:bg-red-900/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                        </tr>
                        `;

                                    });
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    tbody.innerHTML = `
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Terjadi kesalahan saat mencari produk.
                                        </td>
                                    </tr>
                                    `;
                                });
                        }
                    }, 300); // Delay 300ms untuk debouncing
                });
            });

            // Fungsi untuk menghapus produk
            function deleteProduct(productId) {
                if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/products/${productId}`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    form.appendChild(methodInput);
                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    @endpush
</x-app-layout>
