<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-box text-xl"></i>
                    </div>
                    {{ __('Detail Produk') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    <a href="{{ route('products.index') }}" class="hover:text-emerald-500 transition-colors">Produk</a>
                    <span class="mx-1">•</span>
                    <span>{{ $product->name }}</span>
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    <i class="ti ti-arrow-left text-base"></i>
                    <span>Kembali</span>
                </a>
                <a href="{{ route('products.edit', $product) }}" class="btn-warning">
                    <i class="ti ti-edit text-base"></i>
                    <span>Edit</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-100">
            <!-- Left Column: Image & Main Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Product Image -->
                <div class="card p-6 flex flex-col items-center text-center">
                    <div class="w-48 h-48 rounded-2xl bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center overflow-hidden mb-4 border border-gray-100 dark:border-gray-700">
                        @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                        @else
                        <i class="ti ti-box text-6xl text-gray-300 dark:text-gray-600"></i>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $product->code }}</p>

                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30">
                            {{ $product->unit->name ?? 'Pcs' }}
                        </span>
                    </div>
                </div>

                <!-- Stock Summary -->
                <div class="card p-6">
                    <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Ringkasan Stok</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700">
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Total Stok</span>
                            <span class="block text-xl font-bold text-gray-800 dark:text-gray-100">{{ $product->stock }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700">
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Min. Stok</span>
                            <span class="block text-xl font-bold text-gray-800 dark:text-gray-100">{{ $product->min_stock }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details & Tabs -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="card p-6">
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700/50">
                        <i class="ti ti-info-circle text-emerald-500 text-lg"></i>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Informasi Detail</h3>
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                        <div class="col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $product->supplier->name ?? '-' }}</dd>
                        </div>
                        <div class="col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Metode Stok</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $product->stock_method }}</dd>
                        </div>
                        <div class="col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Beli</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</dd>
                        </div>
                        <div class="col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Jual</dt>
                            <dd class="mt-1 text-sm font-semibold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</dd>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $product->description ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Units Table -->
                <div class="card overflow-hidden">
                    <div class="p-6 pb-2 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700/50">
                        <i class="ti ti-ruler-2 text-emerald-500 text-lg"></i>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Satuan & Konversi</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Satuan</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Konversi</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Harga Beli</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($product->units as $unit)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $unit->unit->name }}
                                        @if($unit->is_base_unit)
                                        <span class="badge badge-success ml-2">Dasar</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $unit->conversion_factor }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        Rp {{ number_format($unit->purchase_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                                        Rp {{ number_format($unit->selling_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data satuan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Product Batches -->
                <div class="card overflow-hidden">
                    <div class="p-6 pb-2 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700/50">
                        <i class="ti ti-boxes text-emerald-500 text-lg"></i>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Batch Produk</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Kode Batch</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Stok Sisa</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tgl Masuk</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Expired</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($product->batches as $batch)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $batch->batch_code }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $batch->remaining_stock }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $batch->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        @if($batch->expire_date)
                                        <span class="{{ $batch->expire_date < now() ? 'text-red-500 font-bold' : '' }}">
                                            {{ \Carbon\Carbon::parse($batch->expire_date)->format('d M Y') }}
                                        </span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data batch
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Stock Movement History -->
                <div class="card overflow-hidden">
                    <div class="p-6 pb-2 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700/50">
                        <i class="ti ti-history text-emerald-500 text-lg"></i>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Riwayat Stok</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Referensi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($product->stockMovements()->latest()->take(10)->get() as $movement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $movement->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($movement->type == 'in')
                                        <span class="badge badge-success">Masuk</span>
                                        @else
                                        <span class="badge badge-error">Keluar</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-mono font-semibold {{ $movement->type == 'in' ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $movement->type == 'in' ? '+' : '-' }}{{ abs($movement->quantity) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $movement->reference_type }} #{{ $movement->reference_id }}
                                        @if($movement->description)
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $movement->description }}</div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada riwayat stok
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