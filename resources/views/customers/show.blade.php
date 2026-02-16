<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-user-circle text-xl"></i>
                    </div>
                    {{ __('Detail Pelanggan') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    Informasi lengkap dan riwayat transaksi pelanggan
                </p>
            </div>

            <a href="{{ route('customers.index') }}" class="btn-secondary flex items-center gap-2">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-100">
            <!-- Left Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div class="card p-6 text-center">
                    <div class="w-24 h-24 mx-auto bg-emerald-100 dark:bg-emerald-500/20 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-4xl font-bold mb-4">
                        {{ substr($customer->nama, 0, 1) }}
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $customer->nama }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $customer->nik }}</p>

                    <div class="flex justify-center gap-3">
                        <a href="{{ route('sales.create') }}" class="btn-primary btn-sm flex items-center gap-2">
                            <i class="ti ti-shopping-cart"></i> Transaksi
                        </a>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn-warning btn-sm flex items-center gap-2">
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
                            <span class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $customer->sales->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
                            <span class="text-sm text-emerald-600 dark:text-emerald-400">Total Belanja</span>
                            <span class="text-base font-bold text-emerald-700 dark:text-emerald-300">
                                Rp {{ number_format($customer->sales->sum('total_amount'), 0, ',', '.') }}
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
                        <i class="ti ti-map-pin text-emerald-500"></i> Alamat Lengkap
                    </h3>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700">
                        <p class="text-gray-700 dark:text-gray-300">{{ $customer->alamat }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $customer->desa_nama }}, {{ $customer->kecamatan_nama }}, {{ $customer->kabupaten_nama }}, {{ $customer->provinsi_nama }}
                        </p>
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="card overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <i class="ti ti-history text-emerald-500"></i> Riwayat Transaksi
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal & Faktur</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @forelse($customer->sales as $sale)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $sale->invoice_number }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $sale->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($sale->trashed())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            Dibatalkan
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Selesai
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('sales.show', $sale) }}" class="btn-action btn-secondary inline-flex" title="Detail">
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
                                            <p>Belum ada riwayat transaksi.</p>
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