<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    {{ __('Customers') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola data pelanggan dalam sistem</p>
            </div>

            <div class="mt-4 sm:mt-0 flex space-x-2">
                <div class="relative text-gray-700 focus-within:text-gray-800">
                    <input type="text" id="searchInput" placeholder="Cari pelanggan..."
                        class="pl-10 pr-4 py-2 w-full sm:w-64 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-300"
                        autofocus>
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500 absolute left-3 top-2.5"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <a href="{{ route('customers.template.download') }}"
                    class="p-2 text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 transition-colors rounded-full hover:bg-green-50 dark:hover:bg-green-900/50"
                    title="Download Template Excel">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>

                <a href="{{ route('customers.create') }}"
                    class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 transition-colors rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </a>
            </div>



        </div>

        <!-- Content Area -->
        <div
            class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">
            <!-- Success Message -->
            @if (session('success'))
                <div class="m-6 rounded-lg bg-green-50 dark:bg-green-900/50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400 dark:text-green-300" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                NIK</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Nama</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Alamat</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Total Penjualan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Jumlah Total</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800"
                        id="customersTableBody">
                        @forelse ($customers as $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td
                                    class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $customer->nik }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $customer->nama }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ Str::limit($customer->alamat, 30) }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block">
                                        {{ $customer->desa_nama }}, {{ $customer->kecamatan_nama }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        {{ $customer->sales_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($customer->sales_sum_total_amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('customers.show', $customer->id) }}"
                                            class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 transition-colors rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                            class="p-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200 transition-colors rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200 transition-colors rounded-full hover:bg-red-50 dark:hover:bg-red-900/50"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
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
                                <td colspan="6" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada
                                            pelanggan ditemukan</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Tambahkan pelanggan
                                            baru untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-6">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const customersTableBody = document.getElementById('customersTableBody');
                let debounceTimer;

                function fetchCustomers(searchQuery = '') {
                    // Tampilkan loading state
                    customersTableBody.innerHTML = `
                <tr>
            <td colspan="6" class="px-6 py-8 text-center">
                <div class="flex justify-center">
                    <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Mencari pelanggan...</p>
            </td>
        </tr>
            `;

                    fetch(`/customers?search=${encodeURIComponent(searchQuery)}&_ajax=1`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(response => {
                            const customers = response.data || []; // Pastikan selalu berupa array
                            customersTableBody.innerHTML = '';

                            if (customers.length === 0) {
                                customersTableBody.innerHTML = `
                             <tr>
                                <td colspan="6" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada pelanggan ditemukan</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Coba gunakan kata kunci pencarian yang berbeda</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                                return;
                            }

                            customers.forEach(customer => {
                                const row = document.createElement('tr');
                                row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';

                                row.innerHTML = `
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                        ${customer.nik || '-'}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">${customer.nama || '-'}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                        ${customer.alamat ? customer.alamat.substring(0, 30) : '-'}
                        <span class="text-xs text-gray-500 dark:text-gray-400 block">
                            ${customer.desa_nama || '-'}, ${customer.kecamatan_nama || '-'}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            ${customer.sales_count || 0}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                        Rp ${new Intl.NumberFormat('id-ID').format(customer.sales_sum_total_amount || 0)}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4">
                        <div class="flex items-center space-x-3">
                            ${actionButtons(customer.id)}
                        </div>
                    </td>
                `;

                                customersTableBody.appendChild(row);
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            customersTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-red-500">
                        Terjadi kesalahan saat mengambil data: ${error.message}
                    </td>
                </tr>
            `;
                        });
                }

                function actionButtons(id) {
                    return `
                <a href="/customers/${id}"
                    class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 transition-colors rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>

                <a href="/customers/${id}/edit"
                    class="p-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200 transition-colors rounded-full hover:bg-indigo-50 dark:hover:bg-indigo-900/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>

                <button onclick="deleteCustomer(${id})"
                    class="p-2 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200 transition-colors rounded-full hover:bg-red-50 dark:hover:bg-red-900/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
                }

                // Fungsi pencarian dengan debounce
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        fetchCustomers(this.value);
                    }, 300);
                });

                // Inisialisasi pertama
                fetchCustomers();

                // Fungsi delete global
                window.deleteCustomer = function(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/customers/${id}`;

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
