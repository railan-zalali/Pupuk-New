<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Pesanan Pembelian') }}
            </h2>
            <div class="flex justify-between items-center">
                <div>
                    <a href="{{ route('purchases.create') }}"
                        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Buat Pembelian Baru
                    </a>
                </div>
                <div class="w-64 ml-4">
                    <input type="text" id="searchInput" placeholder="Cari pembelian..."
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
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Faktur</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Pemasok</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Jumlah Total</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($purchases as $purchase)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            {{ $purchase->date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">{{ $purchase->invoice_number }}</td>
                                        <td class="px-6 py-4">{{ $purchase->supplier->name }}</td>
                                        <td class="px-6 py-4">Rp
                                            {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            @if ($purchase->trashed())
                                                <span
                                                    class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                                    Batal
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                                    Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 space-x-2">
                                            <a href="{{ route('purchases.show', $purchase) }}"
                                                class="text-blue-600 hover:text-blue-900">Lihat</a>
                                            @unless ($purchase->trashed())
                                                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pembelian ini?')">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @endunless
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada pembelian ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
