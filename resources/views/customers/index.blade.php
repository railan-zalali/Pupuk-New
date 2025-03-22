<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    {{ __('Customers') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">Kelola data pelanggan dalam sistem</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="w-full sm:w-auto order-2 sm:order-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Cari pelanggan..."
                            class="pl-10 pr-4 py-2 w-full sm:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            autofocus>
                    </div>
                </div>

                <div class="w-full sm:w-auto order-1 sm:order-2">
                    <a href="{{ route('customers.create') }}"
                        class="flex justify-center items-center w-full sm:w-auto rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Pelanggan
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
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

                    <div class="overflow-x-auto relative bg-white rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        NIK
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Nama
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Alamat
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Total Penjualan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Jumlah Total
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="customersTableBody">
                                @forelse ($customers as $customer)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $customer->nik }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $customer->nama }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ Str::limit($customer->alamat, 30) }}
                                            <span class="text-xs text-gray-500 block">
                                                {{ $customer->desa_nama }}, {{ $customer->kecamatan_nama }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $customer->sales_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            Rp {{ number_format($customer->sales_sum_total_amount ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('customers.show', $customer->id) }}"
                                                    class="text-green-600 hover:text-green-900 inline-flex items-center">
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
                                                <a href="{{ route('customers.edit', $customer->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('customers.destroy', $customer->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="h-10 w-10 text-gray-400 mb-3" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-gray-500 text-lg font-medium">Tidak ada pelanggan
                                                    ditemukan.</p>
                                                <p class="text-gray-400 text-sm mt-1">Tambahkan pelanggan baru untuk
                                                    memulai.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const customersTableBody = document.getElementById('customersTableBody');

            // Fungsi debounce untuk mencegah terlalu banyak request
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(context, args);
                    }, wait);
                };
            }

            // Fungsi untuk melakukan pencarian
            const performSearch = debounce(function() {
                const searchTerm = searchInput.value.trim();

                // Tampilkan loader atau indikasi loading
                customersTableBody.innerHTML =
                    '<tr><td colspan="6" class="px-6 py-8 text-center"><div class="flex justify-center"><svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div><p class="mt-2 text-gray-500">Mencari pelanggan...</p></td></tr>';

                // Fetch API untuk mendapatkan hasil pencarian
                fetch(`/customers?search=${encodeURIComponent(searchTerm)}&_ajax=1`)
                    .then(response => response.json())
                    .then(data => {
                        // Clear table
                        customersTableBody.innerHTML = '';

                        if (data.length === 0) {
                            // Jika tidak ada hasil
                            customersTableBody.innerHTML =
                                '<tr><td colspan="6" class="px-6 py-8 text-center"><div class="flex items-center justify-center flex-col"><svg class="h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><p class="text-gray-500 text-lg font-medium">Pelanggan tidak ditemukan</p><p class="text-gray-400 text-sm mt-1">Coba gunakan kata kunci pencarian yang berbeda</p></div></td></tr>';
                            return;
                        }

                        // Tampilkan hasil
                        data.forEach(customer => {
                            const row = document.createElement('tr');
                            row.className = 'hover:bg-gray-50 transition-colors';

                            // Batasi alamat ke 20 karakter
                            const shortAddress = customer.alamat ? (customer.alamat.length >
                                30 ?
                                customer.alamat.substring(0, 30) + '...' : customer.alamat
                            ) : '-';

                            // Format angka untuk total amount
                            const formattedAmount = new Intl.NumberFormat('id-ID').format(
                                customer.sales_sum_total_amount || 0);

                            row.innerHTML = `
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">${customer.nik}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">${customer.nama}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    ${shortAddress}
                                    <span class="text-xs text-gray-500 block">
                                        ${customer.desa_nama}, ${customer.kecamatan_nama}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        ${customer.sales_count || 0}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    Rp ${formattedAmount}
                                </td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="/customers/${customer.id}" class="text-green-600 hover:text-green-900 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail
                                        </a>
                                        <a href="/customers/${customer.id}/edit" class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <button onclick="deleteCustomer(${customer.id})" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            `;

                            customersTableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        customersTableBody.innerHTML =
                            '<tr><td colspan="6" class="px-6 py-8 text-center text-red-500">Terjadi kesalahan saat mengambil data</td></tr>';
                    });
            }, 500); // 500ms debounce

            // Tambahkan event listener untuk input search
            searchInput.addEventListener('input', performSearch);

            // Fungsi untuk menghapus pelanggan
            window.deleteCustomer = function(customerId) {
                if (confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/customers/${customerId}`;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);

                    form.submit();
                }
            }
        });
    </script>
</x-app-layout>
