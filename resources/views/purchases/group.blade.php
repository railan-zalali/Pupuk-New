<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('Grup Pembelian') }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Grup: {{ $purchaseGroup->group_number }} | Tanggal: {{ \Carbon\Carbon::parse($purchaseGroup->date)->format('d/m/Y') }}
                </p>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ route('purchases.index') }}"
                    class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Group Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $purchaseGroup->purchases->count() }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Purchase Orders</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $purchaseGroup->purchases->unique('supplier_id')->count() }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Suppliers</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $purchaseGroup->purchases->sum(function($purchase) { return $purchase->purchaseDetails->sum('quantity'); }) }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Items</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">Rp {{ number_format($purchaseGroup->purchases->sum('total_amount'), 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Amount</div>
                </div>
            </div>
            
            @if($purchaseGroup->notes)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan:</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $purchaseGroup->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Purchase Orders by Supplier -->
        @foreach($purchaseGroup->purchases->groupBy('supplier.name') as $supplierName => $supplierPurchases)
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <!-- Supplier Header -->
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $supplierName }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $supplierPurchases->count() }} Purchase Order(s) | 
                                Total: Rp {{ number_format($supplierPurchases->sum('total_amount'), 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Contact:</div>
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $supplierPurchases->first()->supplier->phone ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Orders Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                    Purchase Number
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                    Due Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                    Items
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                    Amount
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @foreach($supplierPurchases as $purchase)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $purchase->purchase_number }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $purchase->invoice_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $purchase->due_date ? \Carbon\Carbon::parse($purchase->due_date)->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ $purchase->purchaseDetails->count() }} item(s)
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Qty: {{ $purchase->purchaseDetails->sum('quantity') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($purchase->trashed())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Batal
                                            </span>
                                        @elseif ($purchase->isReceived())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Diterima
                                            </span>
                                        @elseif ($purchase->isPartiallyReceived())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Diterima Sebagian
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('purchases.show', $purchase) }}"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-200 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/50"
                                                title="View Details">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            @if (!$purchase->trashed() && $purchase->isPending())
                                                <a href="{{ route('purchases.receipt', $purchase) }}"
                                                    class="p-2 text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-200 rounded-full hover:bg-green-50 dark:hover:bg-green-900/50"
                                                    title="Record Receipt">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </a>
                                            @elseif (!$purchase->trashed() && $purchase->isPartiallyReceived())
                                                <a href="{{ route('purchases.receipt', $purchase) }}"
                                                    class="p-2 text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-200 rounded-full hover:bg-yellow-50 dark:hover:bg-yellow-900/50"
                                                    title="Continue Receipt">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Product Details for this Supplier -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Product Details:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($supplierPurchases->flatMap->purchaseDetails->groupBy('product.name') as $productName => $details)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $productName }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    Total Qty: {{ $details->sum('quantity') }} {{ $details->first()->product->unit ?? '' }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                    Avg Price: Rp {{ number_format($details->avg('price'), 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>