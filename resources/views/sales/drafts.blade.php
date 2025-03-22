<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Draft Transaksi') }}
                    </span>
                </h2>
                <p class="mt-1 text-sm text-gray-600">Kelola draft transaksi penjualan Anda</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="w-full sm:w-auto order-2 sm:order-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Cari draft..."
                            class="pl-10 pr-4 py-2 w-full sm:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            autofocus>
                    </div>
                </div>

                <div class="w-full sm:w-auto order-1 sm:order-2">
                    <a href="{{ route('sales.create') }}"
                        class="flex justify-center items-center w-full sm:w-auto rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buat Penjualan Baru
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-medium">Draft Transaksi</h3>
                <a href="{{ route('sales.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Lihat Transaksi Selesai
                </a>
            </div>

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
                        <table class="min-w-full divide-y divide-gray-200 rounded-md">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Dibuat Tanggal</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Faktur</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Pelanggan</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Jumlah Total</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Pembayaran</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($drafts as $draft)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ \Carbon\Carbon::parse($draft->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">{{ $draft->invoice_number }}</td>
                                        <td class="px-6 py-4">{{ $draft->customer->nama ?? '-' }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($draft->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $draft->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($draft->payment_method) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                                                Draft
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 space-x-2">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('sales.edit', $draft) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 transition-colors inline-flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>

                                                <a href="{{ route('sales.complete-draft', $draft) }}"
                                                    class="text-green-600 hover:text-green-900 transition-colors inline-flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Selesaikan
                                                </a>

                                                <form action="{{ route('sales.destroy', $draft) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 transition-colors inline-flex items-center"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus draft ini?')">
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
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-10 w-10 text-gray-400 mb-3" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <p class="text-gray-500 text-lg font-medium">Tidak ada draft transaksi
                                                </p>
                                                <p class="text-gray-400 text-sm mt-1">Buat draft transaksi baru dengan
                                                    klik "Simpan sebagai Draft" saat membuat transaksi baru.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $drafts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
