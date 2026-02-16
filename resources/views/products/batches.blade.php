<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-boxes text-xl"></i>
                    </div>
                    Detail Batch - {{ $product->name ?? 'Produk Tidak Ditemukan' }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    <a href="{{ route('products.index') }}" class="hover:text-emerald-500 transition-colors">Produk</a>
                    <span class="mx-1">•</span>
                    <span>Kelola Batch</span>
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    <i class="ti ti-arrow-left text-base"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        @if($product)
        <!-- Product Info Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up delay-100">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 text-white shadow-lg">
                <i class="ti ti-box-seam absolute -right-6 -bottom-6 text-9xl opacity-10"></i>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                        <i class="ti ti-box-seam text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-emerald-100 text-sm font-medium">Stok Total</p>
                        <p class="text-2xl font-bold">
                            {{ number_format($product->stock ?? 0) }}
                            <span class="text-xs font-normal bg-white/20 px-2 py-0.5 rounded-full ml-1">
                                {{ $product->unit->name ?? 'Unit' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 p-6 text-white shadow-lg">
                <i class="ti ti-stack-2 absolute -right-6 -bottom-6 text-9xl opacity-10"></i>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                        <i class="ti ti-stack-2 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Batch</p>
                        <p class="text-2xl font-bold">{{ $batches->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 p-6 text-white shadow-lg">
                <i class="ti ti-circle-check absolute -right-6 -bottom-6 text-9xl opacity-10"></i>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                        <i class="ti ti-circle-check text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-amber-100 text-sm font-medium">Batch Tersedia</p>
                        <p class="text-2xl font-bold">{{ $availableBatches->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 p-6 text-white shadow-lg">
                <i class="ti ti-tag absolute -right-6 -bottom-6 text-9xl opacity-10"></i>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                        <i class="ti ti-tag text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Harga Jual</p>
                        <p class="text-xl font-bold">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Batches (FIFO) -->
        <div class="card overflow-hidden animate-fade-in-up delay-200">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 px-6 py-4 border-b border-emerald-500/20">
                <h4 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="ti ti-sort-descending"></i>
                    Batch Tersedia (FIFO)
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white">
                        {{ $availableBatches->count() }}
                    </span>
                </h4>
            </div>

            <div class="overflow-x-auto">
                @if($availableBatches->count() > 0)
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i class="ti ti-hash"></i> No. Batch</span>
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i class="ti ti-calendar"></i> Tgl Produksi</span>
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i class="ti ti-calendar-time"></i> Expired</span>
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i class="ti ti-package"></i> Sisa Qty</span>
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i class="ti ti-cash"></i> H. Beli</span>
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i class="ti ti-clock"></i> Masuk</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($availableBatches as $batch)
                        @php
                        $expiryDate = $batch->expire_date ? \Carbon\Carbon::parse($batch->expire_date) : null;
                        $isExpired = $expiryDate && $expiryDate->isPast();
                        $daysToExpiry = $expiryDate ? $expiryDate->diffInDays(now()) : null;
                        $isExpiringSoon = $expiryDate && !$isExpired && $daysToExpiry <= 30;
                            $isCritical=$expiryDate && !$isExpired && $daysToExpiry <=7;

                            $rowClass='' ;
                            if ($isExpired) {
                            $rowClass='bg-red-50 dark:bg-red-900/10 hover:bg-red-100 dark:hover:bg-red-900/20' ;
                            } elseif ($isCritical) {
                            $rowClass='bg-orange-50 dark:bg-orange-900/10 hover:bg-orange-100 dark:hover:bg-orange-900/20' ;
                            } else {
                            $rowClass='hover:bg-gray-50 dark:hover:bg-gray-800/50' ;
                            }
                            @endphp
                            <tr class="{{ $rowClass }} transition-colors duration-200">
                            <td class="px-6 py-4 align-top">
                                <div class="flex flex-col gap-1">
                                    <span class="font-mono text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                        {{ $batch->batch_code }}
                                    </span>
                                    <div class="flex gap-1">
                                        @if($isExpired)
                                        <span class="badge badge-error text-[10px]">EXPIRED</span>
                                        @elseif($isCritical)
                                        <span class="badge badge-warning text-[10px]">CRITICAL</span>
                                        @elseif($isExpiringSoon)
                                        <span class="badge badge-info text-[10px]">EXPIRING</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 align-top">
                                {{ $batch->production_date ? \Carbon\Carbon::parse($batch->production_date)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm align-top">
                                @if($expiryDate)
                                <div class="flex flex-col">
                                    <span class="font-medium {{ $isExpired ? 'text-red-600 dark:text-red-400' : ($isCritical ? 'text-orange-600 dark:text-orange-400' : ($isExpiringSoon ? 'text-blue-600 dark:text-blue-400' : 'text-gray-800 dark:text-gray-200')) }}">
                                        {{ $expiryDate->format('d/m/Y') }}
                                    </span>
                                    @if($isExpired)
                                    <span class="text-xs text-red-500">Expired {{ $expiryDate->diffForHumans() }}</span>
                                    @elseif($daysToExpiry !== null)
                                    <span class="text-xs text-gray-500">{{ $daysToExpiry }} hari lagi</span>
                                    @endif
                                </div>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold {{ $batch->remaining_stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ number_format($batch->remaining_stock, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-gray-400">dari {{ number_format($batch->stock, 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200 align-top">
                                Rp {{ number_format($batch->purchase_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 align-top">
                                {{ $batch->created_at->format('d/m/Y H:i') }}
                            </td>
                            </tr>
                            @empty
                            <!-- This should not happen due to if check above, but good for safety -->
                            @endforelse
                    </tbody>
                </table>
                @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center mx-auto mb-4 text-blue-500">
                        <i class="ti ti-info-circle text-3xl"></i>
                    </div>
                    <p class="text-base text-gray-600 dark:text-gray-300 font-medium">Tidak ada batch tersedia</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada batch yang masuk atau semua stok habis.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- All Batches History -->
        <div class="card overflow-hidden animate-fade-in-up delay-300">
            <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-6 py-4 border-b border-gray-700/50">
                <h4 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="ti ti-list"></i>
                    Semua Riwayat Batch
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white">
                        {{ $batches->count() }}
                    </span>
                </h4>
            </div>

            <div class="overflow-x-auto">
                @if($batches->count() > 0)
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Batch</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Produksi</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Expired</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Awal</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sisa</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($batches as $batch)
                        @php
                        $expiryDate = $batch->expire_date ? \Carbon\Carbon::parse($batch->expire_date) : null;
                        $isExpired = $expiryDate && $expiryDate->isPast();
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                {{ $batch->batch_code }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $batch->production_date ? \Carbon\Carbon::parse($batch->production_date)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($expiryDate)
                                <span class="{{ $isExpired ? 'text-red-500' : 'text-gray-600 dark:text-gray-400' }}">
                                    {{ $expiryDate->format('d/m/Y') }}
                                </span>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">
                                {{ number_format($batch->stock, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold {{ $batch->remaining_stock > 0 ? 'text-gray-800 dark:text-gray-200' : 'text-red-500' }}">
                                {{ number_format($batch->remaining_stock, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($batch->remaining_stock <= 0)
                                    <span class="badge badge-error text-[10px]">Habis</span>
                                    @elseif($batch->remaining_stock < $batch->stock)
                                        <span class="badge badge-warning text-[10px]">Sebagian</span>
                                        @else
                                        <span class="badge badge-success text-[10px]">Penuh</span>
                                        @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $batch->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-8 text-center">
                    <p class="text-sm text-gray-500">Tidak ada riwayat batch.</p>
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="flex items-start gap-4 p-4 rounded-xl bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200">
            <i class="ti ti-alert-circle text-xl flex-shrink-0 mt-0.5"></i>
            <div>
                <h3 class="font-bold">Produk tidak ditemukan</h3>
                <p class="text-sm mt-1">Data produk yang Anda cari tidak tersedia di sistem.</p>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>