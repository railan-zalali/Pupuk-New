<x-app-layout>
    <x-slot name="header">Detail Pembelian</x-slot>

    <div class="space-y-5">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 animate-fade-in-up">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-shopping-cart text-emerald-600 dark:text-emerald-400 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                        {{ $purchase->invoice_number }}
                    </h2>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5 flex items-center gap-1.5">
                        <i class="ti ti-calendar text-xs"></i>
                        {{ \Carbon\Carbon::parse($purchase->date)->translatedFormat('d F Y, H:i') }}
                    </p>
                    <div class="flex items-center gap-2 mt-2">
                        @if ($purchase->trashed())
                        <span class="badge badge-danger"><i class="ti ti-circle-x text-xs"></i> Dibatalkan</span>
                        @elseif ($purchase->isReceived())
                        <span class="badge badge-success"><i class="ti ti-circle-check text-xs"></i> Diterima</span>
                        @elseif ($purchase->isPartiallyReceived())
                        <span class="badge badge-warning"><i class="ti ti-clock text-xs"></i> Sebagian Diterima</span>
                        @else
                        <span class="badge badge-info"><i class="ti ti-clock text-xs"></i> Pending</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('purchases.print', $purchase) }}" target="_blank" class="btn-ghost btn-sm">
                    <i class="ti ti-printer text-base"></i> Print Invoice
                </a>

                @if (!$purchase->isReceived() && !$purchase->trashed())
                <a href="{{ route('purchases.receipt', $purchase) }}"
                    class="btn-primary btn-sm">
                    <i class="ti ti-box text-base"></i> Terima Barang
                </a>
                @endif
            </div>
        </div>

        <!-- Status Banner if Trashed -->
        @if ($purchase->trashed())
        <div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 animate-fade-in-up">
            <i class="ti ti-alert-triangle text-red-500 text-lg flex-shrink-0"></i>
            <div>
                <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Pembelian Dibatalkan</h3>
                <p class="text-xs text-red-600 dark:text-red-300 mt-0.5">
                    Dibatalkan pada {{ $purchase->deleted_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 animate-fade-in-up stagger-1">
            <!-- Supplier Information -->
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="ti ti-building-store text-emerald-500"></i> Informasi Supplier
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Nama Supplier</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $purchase->supplier->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Email</span>
                        <span class="text-sm text-gray-700 dark:text-gray-200">{{ $purchase->supplier->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Telepon</span>
                        <span class="text-sm text-gray-700 dark:text-gray-200">{{ $purchase->supplier->phone ?? '-' }}</span>
                    </div>
                    <div class="py-2">
                        <span class="text-xs text-gray-400">Alamat</span>
                        <p class="text-sm text-gray-700 dark:text-gray-200 mt-1">
                            {{ $purchase->supplier->address ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Purchase Information -->
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="ti ti-file-info text-emerald-500"></i> Informasi Transaksi
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">No. Invoice</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $purchase->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Tanggal Buat</span>
                        <span class="text-sm text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Jatuh Tempo</span>
                        <span class="text-sm text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($purchase->due_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-xs text-gray-400">Dibuat Oleh</span>
                        <span class="text-sm text-gray-700 dark:text-gray-200">{{ $purchase->user->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Status -->
        <div class="card p-5 animate-fade-in-up stagger-2">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <i class="ti ti-progress text-emerald-500"></i> Status Penerimaan Barang
            </h3>

            @php
            $totalOrdered = $purchase->getTotalOrderedQuantity();
            $totalReceived = $purchase->getTotalReceivedQuantity();
            $percentage = $totalOrdered > 0 ? round(($totalReceived / $totalOrdered) * 100) : 0;
            $colorClass = $percentage >= 100 ? 'bg-emerald-500' : ($percentage > 0 ? 'bg-amber-500' : 'bg-gray-300 dark:bg-gray-600');
            @endphp

            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $totalReceived }} dari {{ $totalOrdered }} item diterima
                </span>
                <span class="text-sm font-bold text-gray-800 dark:text-gray-100">
                    {{ $percentage }}%
                </span>
            </div>

            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                <div class="{{ $colorClass }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
            </div>
        </div>

        <!-- Purchase Items Table -->
        <div class="card overflow-hidden animate-fade-in-up stagger-3">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-700/40">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="ti ti-box text-emerald-500"></i> Item Pembelian
                </h3>
                <span class="badge badge-primary">{{ $purchase->purchaseDetails->count() }} item</span>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-10">No</th>
                            <th>Produk</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase->purchaseDetails as $index => $detail)
                        <tr>
                            <td class="text-center text-gray-500">{{ $index + 1 }}</td>
                            <td>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $detail->product->name }}</p>
                                    @if($detail->product->code)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $detail->product->code }}</p>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <p class="text-sm text-gray-700 dark:text-gray-200 tabular-nums">
                                    Rp {{ number_format($detail->purchase_price, 0, ',', '.') }}
                                </p>
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-sm font-semibold text-gray-800 dark:text-gray-100 tabular-nums">
                                    {{ $detail->quantity }} {{ optional($detail->unit)->abbreviation }}
                                </span>
                                @if($detail->conversion_factor > 1)
                                <p class="text-xs text-gray-400 mt-1">
                                    = {{ $detail->base_quantity }} {{ optional($detail->product->baseUnit)->abbreviation ?? 'pcs' }}
                                </p>
                                @endif
                            </td>
                            <td class="text-right">
                                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100 tabular-nums">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-emerald-50/50 dark:bg-emerald-500/5">
                            <td colspan="4" class="text-right text-sm font-semibold text-gray-800 dark:text-gray-100 px-6 py-4">Total</td>
                            <td class="text-right px-6 py-4">
                                <span class="text-lg font-bold text-emerald-700 dark:text-emerald-400 tabular-nums">
                                    Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Receipt History -->
        @if ($purchase->receipts->count() > 0)
        <div class="card overflow-hidden animate-fade-in-up stagger-4">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-700/40">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="ti ti-history text-emerald-500"></i> Riwayat Penerimaan
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">No. Penerimaan</th>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium">Diterima Oleh</th>
                            <th class="px-6 py-3 font-medium">Bukti</th>
                            <th class="px-6 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @foreach ($purchase->receipts as $receipt)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                {{ $receipt->receipt_number }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $receipt->receipt_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $receipt->user->name }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($receipt->receipt_file)
                                <a href="{{ Storage::url($receipt->receipt_file) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium">
                                    <i class="ti ti-paperclip text-sm"></i> Lihat File
                                </a>
                                @else
                                <span class="text-gray-400 italic">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('purchases.receipt', $purchase) }}"
                                    class="text-gray-400 hover:text-emerald-600 transition-colors"
                                    title="Lihat Detail">
                                    <i class="ti ti-eye text-lg"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if ($purchase->notes)
        <div class="card p-5 animate-fade-in-up stagger-5">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3 flex items-center gap-2">
                <i class="ti ti-notes text-emerald-500"></i> Catatan
            </h3>
            <div class="p-4 rounded-xl bg-amber-50/50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/20">
                <p class="text-sm text-gray-700 dark:text-gray-300 italic leading-relaxed">{{ $purchase->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Footer Actions -->
        <div class="flex justify-start space-x-3 pt-4 print:hidden animate-fade-in-up stagger-6">
            <button type="button" onclick="window.history.back()"
                class="btn-ghost">
                <i class="ti ti-arrow-left text-base"></i> Kembali
            </button>

            @unless ($purchase->trashed())
            <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline-block ml-auto">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="btn-danger"
                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pembelian ini? Tindakan ini tidak dapat dibatalkan.')">
                    <i class="ti ti-trash text-base"></i> Batalkan Pembelian
                </button>
            </form>
            @endunless
        </div>
    </div>
</x-app-layout>