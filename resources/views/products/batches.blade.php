<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-boxes text-indigo-600 dark:text-indigo-400 mr-3"></i>
                    Detail Batch - {{ $product->name ?? 'Produk Tidak Ditemukan' }}
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Kelola dan pantau batch produk</p>
            </div>
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    @if($product)
        <!-- Product Info Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-cubes text-2xl opacity-80"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-indigo-100 text-sm font-medium">Stok Total</p>
                        <p class="text-2xl font-bold">
                            {{ number_format($product->stock ?? 0) }}
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 ml-2">
                                {{ $product->unit->name ?? 'Unit' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-layer-group text-2xl opacity-80"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-green-100 text-sm font-medium">Total Batch</p>
                        <p class="text-2xl font-bold">{{ $batches->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl opacity-80"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-yellow-100 text-sm font-medium">Batch Tersedia</p>
                        <p class="text-2xl font-bold">{{ $availableBatches->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tag text-2xl opacity-80"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-blue-100 text-sm font-medium">Harga Jual</p>
                        <p class="text-2xl font-bold">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Batches (FIFO) -->
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h4 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-sort-amount-down mr-3"></i>
                        Batch Tersedia (FIFO)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white ml-3">
                            {{ $availableBatches->count() }}
                        </span>
                    </h4>
                </div>
                <div class="p-6">
                    @if($availableBatches->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-hashtag mr-2"></i>No. Batch
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-calendar-alt mr-2"></i>Tanggal Produksi
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-calendar-times mr-2"></i>Tanggal Kadaluarsa
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-cubes mr-2"></i>Kuantitas Awal
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-box mr-2"></i>Sisa Kuantitas
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-money-bill-wave mr-2"></i>Harga Beli
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-clock mr-2"></i>Tanggal Dibuat
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($availableBatches as $batch)
                                        @php
                                            $expiryDate = $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date) : null;
                                            $isExpired = $expiryDate && $expiryDate->isPast();
                                            $daysToExpiry = $expiryDate ? $expiryDate->diffInDays(now()) : null;
                                            $isExpiringSoon = $expiryDate && !$isExpired && $daysToExpiry <= 30;
                                            $isCritical = $expiryDate && !$isExpired && $daysToExpiry <= 7;
                                            
                                            $rowClass = '';
                                            if ($isExpired) {
                                                $rowClass = 'bg-red-50 dark:bg-red-900/20';
                                            } elseif ($isCritical) {
                                                $rowClass = 'bg-yellow-50 dark:bg-yellow-900/20';
                                            }
                                        @endphp
                                        <tr class="{{ $rowClass }} hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                                                    {{ $batch->batch_number }}
                                                </span>
                                                @if($isExpired)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 ml-2">EXPIRED</span>
                                                @elseif($isCritical)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 ml-2">CRITICAL</span>
                                                @elseif($isExpiringSoon)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 ml-2">EXPIRING</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                {{ $batch->production_date ? \Carbon\Carbon::parse($batch->production_date)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($expiryDate)
                                                    <div>
                                                        <span class="text-sm {{ $isExpired ? 'text-red-600 dark:text-red-400 font-semibold' : ($isCritical ? 'text-yellow-600 dark:text-yellow-400 font-semibold' : ($isExpiringSoon ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-gray-300')) }}">
                                                            {{ $expiryDate->format('d/m/Y') }}
                                                        </span>
                                                        @if($isExpired)
                                                            <div class="text-xs text-red-600 dark:text-red-400 mt-1">Expired {{ $expiryDate->diffForHumans() }}</div>
                                                        @elseif($daysToExpiry !== null)
                                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $daysToExpiry }} hari lagi</div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                {{ number_format($batch->quantity, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $batch->remaining_quantity > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                    {{ number_format($batch->remaining_quantity, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                                Rp {{ number_format($batch->purchase_price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                {{ \Carbon\Carbon::parse($batch->created_at)->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                                    <p class="text-gray-500 dark:text-gray-400">Tidak ada batch tersedia</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mr-3"></i>
                                <p class="text-blue-700 dark:text-blue-300">
                                    Tidak ada batch yang tersedia untuk produk ini.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- All Batches -->
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h4 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-list mr-3"></i>
                        Semua Batch
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white ml-3">
                            {{ $batches->count() }}
                        </span>
                    </h4>
                </div>
                <div class="p-6">
                    @if($batches->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-hashtag mr-2"></i>No. Batch
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-calendar-alt mr-2"></i>Tanggal Produksi
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-calendar-times mr-2"></i>Tanggal Kadaluarsa
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-cubes mr-2"></i>Kuantitas Awal
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-box mr-2"></i>Sisa Kuantitas
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-money-bill-wave mr-2"></i>Harga Beli
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-info-circle mr-2"></i>Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            <i class="fas fa-clock mr-2"></i>Tanggal Dibuat
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($batches as $batch)
                                    @php
                                        $expiryDate = $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date) : null;
                                        $isExpired = $expiryDate && $expiryDate->isPast();
                                        $daysToExpiry = $expiryDate ? $expiryDate->diffInDays(now()) : null;
                                        $isExpiringSoon = $expiryDate && !$isExpired && $daysToExpiry <= 30;
                                        $isCritical = $expiryDate && !$isExpired && $daysToExpiry <= 7;
                                        
                                        $rowClass = '';
                                        if ($isExpired) {
                                            $rowClass = 'bg-red-50 dark:bg-red-900/20';
                                        } elseif ($isCritical) {
                                            $rowClass = 'bg-yellow-50 dark:bg-yellow-900/20';
                                        }
                                    @endphp
                                    <tr class="{{ $rowClass }} hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300">
                                                {{ $batch->batch_number }}
                                            </span>
                                            @if($isExpired)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 ml-2">EXPIRED</span>
                                            @elseif($isCritical)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 ml-2">CRITICAL</span>
                                            @elseif($isExpiringSoon)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 ml-2">EXPIRING</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $batch->production_date ? \Carbon\Carbon::parse($batch->production_date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($expiryDate)
                                                <div>
                                                    <span class="text-sm {{ $isExpired ? 'text-red-600 dark:text-red-400 font-semibold' : ($isCritical ? 'text-yellow-600 dark:text-yellow-400 font-semibold' : ($isExpiringSoon ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-gray-300')) }}">
                                                        {{ $expiryDate->format('d/m/Y') }}
                                                    </span>
                                                    @if($isExpired)
                                                        <div class="text-xs text-red-600 dark:text-red-400 mt-1">Expired {{ $expiryDate->diffForHumans() }}</div>
                                                    @elseif($daysToExpiry !== null)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $daysToExpiry }} hari lagi</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ number_format($batch->quantity, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $batch->remaining_quantity > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                {{ number_format($batch->remaining_quantity, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                            Rp {{ number_format($batch->purchase_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($batch->remaining_quantity <= 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                    <i class="fas fa-times mr-1"></i> Habis
                                                </span>
                                            @elseif($batch->remaining_quantity < $batch->quantity)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                    <i class="fas fa-minus mr-1"></i> Sebagian
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    <i class="fas fa-check mr-1"></i> Penuh
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($batch->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-inbox text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                                <p class="text-gray-500 dark:text-gray-400">Tidak ada batch</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-500 dark:text-yellow-400 mr-3"></i>
                                <p class="text-yellow-700 dark:text-yellow-300">
                                    Tidak ada batch ditemukan untuk produk ini.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mr-3"></i>
                <p class="text-red-700 dark:text-red-300">
                    Produk tidak ditemukan.
                </p>
            </div>
        </div>
    @endif
</div>
</x-app-layout>
