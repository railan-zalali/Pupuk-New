<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Customers') }}
            </h2>
            <div class="flex space-x-4 items-center">
                <a href="{{ route('customers.create') }}"
                    class="inline-flex items-center rounded-md mr-2 bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                    Add New Customer
                </a>
                <div class="w-1/3">
                    <input type="text" id="searchInput" placeholder="Search Customers..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        autofocus>
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

                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500">NIK
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500">
                                        Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500">
                                        Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500">
                                        Village</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500">
                                        District</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500">
                                        Regency</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500">
                                        Province</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500">
                                        Total Sales</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500">
                                        Total Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td class="px-6 py-4 text-sm">{{ $customer->nik }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $customer->nama }}</td>
                                        <td class="px-6 py-4 text-sm ">
                                            {{ Str::limit($customer->alamat, 10) }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $customer->desa_nama }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $customer->kecamatan_nama }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            {{ Str::limit($customer->kabupaten_nama, 10) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $customer->provinsi_nama }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                                {{ $customer->sales_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            Rp
                                            {{ number_format($customer->sales_sum_total_amount ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="{{ route('customers.show', $customer->id) }}"
                                                class="text-green-600">Show</a>
                                            <a href="{{ route('customers.edit', $customer->id) }}"
                                                class="text-blue-600">Edit</a>
                                            <form action="{{ route('customers.destroy', $customer->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const customersTable = document.querySelector('table tbody');

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
                customersTable.innerHTML =
                    '<tr><td colspan="10" class="text-center py-4">Searching...</td></tr>';

                // Fetch API untuk mendapatkan hasil pencarian
                fetch(`/customers?search=${encodeURIComponent(searchTerm)}&_ajax=1`)
                    .then(response => response.json())
                    .then(data => {
                        // Clear table
                        customersTable.innerHTML = '';

                        if (data.length === 0) {
                            // Jika tidak ada hasil
                            customersTable.innerHTML =
                                '<tr><td colspan="10" class="text-center py-4">No customers found</td></tr>';
                            return;
                        }

                        // Tampilkan hasil
                        data.forEach(customer => {
                            const row = document.createElement('tr');

                            // Batasi alamat ke 20 karakter
                            const shortAddress = customer.alamat ? (customer.alamat.length >
                                20 ?
                                customer.alamat.substring(0, 20) + '...' : customer.alamat
                            ) : '-';

                            // Format angka untuk total amount
                            const formattedAmount = new Intl.NumberFormat('id-ID').format(
                                customer.sales_sum_total_amount || 0);

                            row.innerHTML = `
                                <td class="px-6 py-4 text-sm text-gray-500">${customer.nik}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">${customer.nama}</td>
                                <td class="px-6 py-4 text-sm w-auto text-gray-500">${shortAddress}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">${customer.desa_nama}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">${customer.kecamatan_nama}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">${customer.kabupaten_nama}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">${customer.provinsi_nama}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                        ${customer.sales_count || 0}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    Rp ${formattedAmount}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <a href="/customers/${customer.id}" class="text-green-600">Show</a>
                                    <a href="/customers/${customer.id}/edit" class="text-blue-600">Edit</a>
                                    <form action="/customers/${customer.id}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Delete</button>
                                    </form>
                                </td>
                            `;

                            customersTable.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        customersTable.innerHTML =
                            '<tr><td colspan="10" class="text-center py-4">Error loading data</td></tr>';
                    });
            }, 500); // 500ms debounce

            // Tambahkan event listener untuk input search
            searchInput.addEventListener('input', performSearch);
        });
    </script>
</x-app-layout>
