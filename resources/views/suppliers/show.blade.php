<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-building-warehouse text-xl"></i>
                    </div>
                    {{ __('Detail Supplier') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Informasi lengkap dan riwayat pembelian dari supplier
                </p>
            </div>

            <a href="{{ route('suppliers.index') }}" class="btn-secondary flex items-center gap-2">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-100">
            <!-- Left Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div class="card p-6 text-center">
                    <div class="w-24 h-24 mx-auto bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-4xl font-bold mb-4">
                        {{ substr($supplier->name, 0, 2) }}
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $supplier->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $supplier->phone }}</p>

                    <div class="flex justify-center gap-3">
                        <a href="{{ route('purchases.create') }}?supplier_id={{ $supplier->id }}" class="btn-primary btn-sm flex items-center gap-2">
                            <i class="ti ti-shopping-cart-plus"></i> Beli Stok
                        </a>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-warning btn-sm flex items-center gap-2">
                            <i class="ti ti-edit"></i> Edit
                        </a>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="card p-6">
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4">Ringkasan</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi</span>
                            <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $supplier->purchases->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
                            <span class="text-sm text-emerald-600 dark:text-emerald-400">Total Pembelian</span>
                            <span class="text-base font-bold text-emerald-700 dark:text-emerald-300">
                                Rp {{ number_format($supplier->purchases->sum('total_amount'), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Address Card -->
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2 mb-4">
                        <i class="ti ti-map-pin text-emerald-500"></i> Alamat & Keterangan
                    </h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700">
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-1">Alamat</h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $supplier->address }}</p>
                        </div>
                        @if($supplier->description)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700">
                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-1">Keterangan</h4>
                            <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $supplier->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="card overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <i class="ti ti-history text-emerald-500"></i> Riwayat Pembelian
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal & Invoice</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @forelse($supplier->purchases as $purchase)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $purchase->invoice_number }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $purchase->date->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $purchase->purchaseDetails->sum('quantity') }} Item</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($purchase->trashed())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            Void
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Selesai
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('purchases.show', $purchase) }}" class="btn-action btn-secondary inline-flex" title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                                                <i class="ti ti-shopping-cart-off text-xl"></i>
                                            </div>
                                            <p>Belum ada riwayat pembelian.</p>
                                        </div>
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