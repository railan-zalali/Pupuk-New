<x-app-layout>
    <x-slot name="header">Daftar Produk</x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-package text-xl"></i>
                    </div>
                    {{ __('Data Produk') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">Kelola katalog produk, stok, dan harga</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput"
                        placeholder="Cari produk..."
                        class="pl-10 pr-4 py-2.5 w-full sm:w-64 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all shadow-sm">
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('products.import') }}" class="btn-secondary">
                        <i class="ti ti-file-import text-base"></i>
                        <span class="hidden sm:inline">Import</span>
                    </a>
                    <a href="{{ route('products.create') }}" class="btn-primary">
                        <i class="ti ti-plus text-base"></i>
                        <span>Tambah Produk</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="animate-fade-in-up delay-100">
            @if (session('success'))
            <div class="mb-4 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-200">
                <i class="ti ti-check-circle text-xl flex-shrink-0"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
            @endif

            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga Jual</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($products as $product)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="whitespace-nowrap py-3">
                                    <div class="h-10 w-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden border border-gray-200 dark:border-gray-600">
                                        @if ($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}"
                                            alt="{{ $product->name }}" class="h-full w-full object-cover">
                                        @else
                                        <i class="ti ti-photo text-gray-400 dark:text-gray-500"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="font-medium text-xs text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $product->code }}
                                </td>
                                <td class="font-medium text-gray-900 dark:text-white">
                                    {{ $product->name }}
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-600 dark:text-gray-300">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                <td>
                                    @if ($product->actual_stock <= $product->min_stock)
                                        <span class="badge badge-danger">
                                            {{ $product->actual_stock }}
                                        </span>
                                        @else
                                        <span class="badge badge-success">
                                            {{ $product->actual_stock }}
                                        </span>
                                        @endif
                                </td>
                                <td class="font-medium text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('products.show', $product) }}"
                                            class="action-btn-blue" data-tooltip="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        <a href="{{ route('products.edit', $product) }}"
                                            class="action-btn-indigo" data-tooltip="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn-red" data-tooltip="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-package-off text-3xl text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada produk ditemukan</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Tambahkan produk baru untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-800/50">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const productsTableBody = document.querySelector('tbody');
            let debounceTimer;

            // Function to generate Row HTML matching the Blade template
            function generateRowHtml(product) {
                const stockBadge = product.actual_stock <= product.min_stock ?
                    `<span class="badge badge-danger">${product.actual_stock}</span>` :
                    `<span class="badge badge-success">${product.actual_stock}</span>`;

                const imageHtml = product.image_path ?
                    `<img src="/storage/${product.image_path}" alt="${product.name}" class="h-full w-full object-cover">` :
                    `<i class="ti ti-photo text-gray-400 dark:text-gray-500"></i>`;

                // Helper for number formatting
                const formatPrice = (price) => {
                    return new Intl.NumberFormat('id-ID', {
                        maximumFractionDigits: 0
                    }).format(price);
                };

                return `
                        <td class="whitespace-nowrap py-3">
                            <div class="h-10 w-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden border border-gray-200 dark:border-gray-600">
                                ${imageHtml}
                            </div>
                        </td>
                        <td class="font-medium text-xs text-gray-500 dark:text-gray-400 font-mono">
                            ${product.code}
                        </td>
                        <td class="font-medium text-gray-900 dark:text-white">
                            ${product.name}
                        </td>
                        <td>
                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-600 dark:text-gray-300">
                                ${product.category ? product.category.name : '-'}
                            </span>
                        </td>
                        <td>
                            ${stockBadge}
                        </td>
                        <td class="font-medium text-gray-900 dark:text-gray-100">
                            Rp ${formatPrice(product.selling_price)}
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="/products/${product.id}"
                                    class="action-btn-blue" data-tooltip="Detail">
                                    <i class="ti ti-eye"></i>
                                </a>

                                <a href="/products/${product.id}/edit"
                                    class="action-btn-indigo" data-tooltip="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <button onclick="deleteProduct(${product.id})"
                                    class="action-btn-red" data-tooltip="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </td>
                    `;
            }

            function fetchProducts(searchQuery = '') {
                // Show loading state
                productsTableBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-8 h-8 rounded-full border-2 border-emerald-500 border-t-transparent animate-spin"></div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Mencari produk...</p>
                                </div>
                            </td>
                        </tr>
                    `;

                fetch(`/search/products?query=${encodeURIComponent(searchQuery)}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(response => {
                        const products = response.data || [];
                        productsTableBody.innerHTML = '';

                        if (products.length === 0) {
                            productsTableBody.innerHTML = `
                                    <tr>
                                        <td colspan="7" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                                    <i class="ti ti-package-off text-3xl text-gray-400"></i>
                                                </div>
                                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada produk ditemukan</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Coba gunakan kata kunci lain</p>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            return;
                        }

                        products.forEach(product => {
                            const row = document.createElement('tr');
                            row.className = 'group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors';
                            row.innerHTML = generateRowHtml(product);
                            productsTableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        productsTableBody.innerHTML = `
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-red-500">
                                        <div class="flex flex-col items-center gap-1">
                                            <i class="ti ti-alert-circle text-xl"></i>
                                            <span>Terjadi kesalahan saat memuat data</span>
                                        </div>
                                    </td>
                                </tr>
                            `;
                    });
            }

            // Debounce search
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const query = this.value;
                    if (query.length === 0) {
                        // If empty, reload page to restore pagination (simple way)
                        window.location.reload();
                    } else {
                        fetchProducts(query);
                    }
                }, 400);
            });

            // Global delete function
            window.deleteProduct = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/products/${id}`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.innerHTML = `
                            <input type="hidden" name="_token" value="${csrfToken}">
                            ${methodInput.outerHTML}
                        `;

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        });
    </script>
    @endpush
</x-app-layout>