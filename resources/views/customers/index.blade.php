<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-users text-xl"></i>
                    </div>
                    {{ __('Daftar Pelanggan') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Kelola data pelanggan dan riwayat transaksi
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative text-gray-700 focus-within:text-gray-800">
                    <input type="text" id="searchInput" placeholder="Cari nama, alamat, atau NIK..."
                        class="pl-10 pr-4 py-2.5 w-full sm:w-64 rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-700 dark:text-gray-300 transition-all"
                        autofocus>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ti ti-search text-gray-400 dark:text-gray-500"></i>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('customers.template.download') }}"
                        class="btn-secondary flex items-center justify-center gap-2"
                        title="Download Template Excel">
                        <i class="ti ti-file-download text-lg"></i>
                        <span class="hidden sm:inline">Template</span>
                    </a>

                    <a href="{{ route('customers.create') }}"
                        class="btn-primary flex items-center justify-center gap-2">
                        <i class="ti ti-plus text-lg"></i>
                        <span class="hidden sm:inline">Pelanggan Baru</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="card overflow-hidden animate-fade-in-up delay-100">
            <!-- Success Message -->
            @if (session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/10 border-b border-emerald-100 dark:border-emerald-800/30">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="ti ti-circle-check text-emerald-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NIK & Alamat</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keaktifan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Belanja</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800" id="customersTableBody">
                        @forelse ($customers as $customer)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold text-sm">
                                        {{ substr($customer->nama, 0, 2) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-emerald-600 transition-colors">{{ $customer->nama }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $customer->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $customer->nik }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ Str::limit($customer->alamat, 25) }}, {{ $customer->desa_nama }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->sales_count > 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $customer->sales_count ?? 0 }} Transaksi
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($customer->sales_sum_total_amount ?? 0, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('customers.show', $customer->id) }}"
                                        class="btn-action btn-secondary" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </a>

                                    <a href="{{ route('customers.edit', $customer->id) }}"
                                        class="btn-action btn-warning" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>

                                    <button onclick="deleteCustomer({{ $customer->id }})"
                                        class="btn-action btn-danger" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                        <i class="ti ti-users-off text-3xl text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <h3 class="text-gray-900 dark:text-gray-100 text-lg font-medium">Tidak ada pelanggan</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Belum ada data pelanggan yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700/50">
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
                // Loading State
                customersTableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-emerald-500 mb-4"></div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">Mencari data pelanggan...</p>
                                </div>
                            </td>
                        </tr>
                    `;

                fetch(`/customers?search=${encodeURIComponent(searchQuery)}&_ajax=1`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(response => {
                        const customers = response.data || [];
                        customersTableBody.innerHTML = '';

                        if (customers.length === 0) {
                            customersTableBody.innerHTML = `
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                                    <i class="ti ti-search-off text-3xl text-gray-400 dark:text-gray-500"></i>
                                                </div>
                                                <h3 class="text-gray-900 dark:text-gray-100 text-lg font-medium">Tidak ditemukan</h3>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Coba kata kunci lain atau tambah pelanggan baru.</p>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            return;
                        }

                        customers.forEach(customer => {
                            const row = document.createElement('tr');
                            row.className = 'group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors';

                            const initials = customer.nama ? customer.nama.substring(0, 2).toUpperCase() : '??';
                            const salesCount = customer.sales_count || 0;
                            const totalAmount = new Intl.NumberFormat('id-ID').format(customer.sales_sum_total_amount || 0);
                            const alamat = customer.alamat ? customer.alamat.substring(0, 25) : '-';

                            row.innerHTML = `
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold text-sm">
                                                ${initials}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 group-hover:text-emerald-600 transition-colors">${customer.nama}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: ${customer.id}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">${customer.nik || '-'}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                            ${alamat}, ${customer.desa_nama || '-'}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${salesCount > 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'}">
                                            ${salesCount} Transaksi
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            Rp ${totalAmount}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="/customers/${customer.id}" class="btn-action btn-secondary" title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="/customers/${customer.id}/edit" class="btn-action btn-warning" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button onclick="deleteCustomer(${customer.id})" class="btn-action btn-danger" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
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
                                    <td colspan="5" class="px-6 py-4 text-center text-red-500">
                                        <div class="flex items-center justify-center gap-2">
                                            <i class="ti ti-alert-triangle"></i>
                                            <span>Terjadi kesalahan saat memuat data.</span>
                                        </div>
                                    </td>
                                </tr>
                            `;
                    });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetchCustomers(this.value);
                }, 300);
            });

            // Function delete global
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

                    form.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}">`;
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        });
    </script>
    @endpush
</x-app-layout>