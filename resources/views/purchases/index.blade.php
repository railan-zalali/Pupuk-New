<x-app-layout>
    <x-slot name="header">Daftar Pembelian</x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-shopping-cart text-xl"></i>
                    </div>
                    {{ __('Transaksi Pembelian') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">Kelola data pembelian dan stok masuk</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <form action="{{ route('purchases.index') }}" method="GET" class="relative">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari pembelian..."
                        class="pl-10 pr-4 py-2.5 w-full sm:w-64 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all shadow-sm">
                </form>

                <a href="{{ route('purchases.create') }}" class="btn-primary">
                    <i class="ti ti-plus text-base"></i>
                    <span>Tambah Pembelian</span>
                </a>
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
                                <th>Tanggal</th>
                                <th>Faktur</th>
                                <th>Supplier</th>
                                <th>Group</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($purchases as $purchase)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-gray-900 dark:text-gray-100 font-medium">
                                            {{ \Carbon\Carbon::parse($purchase->date)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ \Carbon\Carbon::parse($purchase->date)->diffForHumans() }}
                                        </span>
                                    </div>
                                </td>
                                <td class="font-medium text-gray-900 dark:text-white">
                                    {{ $purchase->invoice_number }}
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300">
                                            {{ substr($purchase->supplier->name, 0, 1) }}
                                        </div>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $purchase->supplier->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($purchase->purchaseGroup)
                                    <a href="{{ route('purchases.group.show', $purchase->purchaseGroup) }}"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 text-xs font-medium hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-colors">
                                        <i class="ti ti-folders text-sm"></i>
                                        {{ $purchase->purchaseGroup->group_number }}
                                    </a>
                                    <div class="text-[10px] text-gray-400 mt-1 pl-1">
                                        {{ $purchase->purchaseGroup->purchases->count() }} PO digabung
                                    </div>
                                    @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="font-bold text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($purchase->trashed())
                                    <span class="badge badge-danger">
                                        <i class="ti ti-circle-x text-xs"></i> Batal
                                    </span>
                                    @elseif ($purchase->isReceived())
                                    <span class="badge badge-success">
                                        <i class="ti ti-circle-check text-xs"></i> Diterima
                                    </span>
                                    @elseif ($purchase->isPartiallyReceived())
                                    <span class="badge badge-warning">
                                        <i class="ti ti-clock text-xs"></i> Sebagian
                                    </span>
                                    @else
                                    <span class="badge badge-info">
                                        <i class="ti ti-clock text-xs"></i> Pending
                                    </span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('purchases.show', $purchase) }}"
                                            class="action-btn-blue"
                                            data-tooltip="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>

                                        @if (!$purchase->trashed() && $purchase->isPending())
                                        <a href="{{ route('purchases.receipt', $purchase) }}"
                                            class="action-btn-emerald"
                                            data-tooltip="Terima Barang">
                                            <i class="ti ti-box"></i>
                                        </a>
                                        @elseif (!$purchase->trashed() && $purchase->isPartiallyReceived())
                                        <a href="{{ route('purchases.receipt', $purchase) }}"
                                            class="action-btn-amber"
                                            data-tooltip="Lanjut Terima">
                                            <i class="ti ti-box"></i>
                                        </a>
                                        @endif

                                        @unless ($purchase->trashed() || !$purchase->isPending())
                                        <form action="{{ route('purchases.destroy', $purchase) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pembelian ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn-red" data-tooltip="Batalkan">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-shopping-cart-off text-3xl text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada pembelian ditemukan</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Coba sesuaikan pencarian atau tambah pembelian baru</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($purchases->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-800/50">
                    {{ $purchases->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>