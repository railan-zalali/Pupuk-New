<x-app-layout>
    <x-slot name="header">Catat Penerimaan</x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between animate-fade-in-up">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i class="ti ti-box-seam text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                        Catat Penerimaan #{{ $purchase->invoice_number }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1.5">
                        <i class="ti ti-calendar text-xs"></i>
                        {{ \Carbon\Carbon::parse($purchase->date)->translatedFormat('d F Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 animate-fade-in-up">
            <i class="ti ti-alert-circle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
            <div>
                <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Terdapat kesalahan:</h3>
                <ul class="mt-1 text-sm text-red-600 dark:text-red-300 list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="card p-6 animate-fade-in-up stagger-1">
            <!-- Purchase Information -->
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="ti ti-file-info text-emerald-500"></i> Informasi Pembelian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30">
                        <p class="text-xs text-gray-400 mb-1">No. Invoice</p>
                        <p class="font-medium text-gray-800 dark:text-gray-100">{{ $purchase->invoice_number }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30">
                        <p class="text-xs text-gray-400 mb-1">Tanggal</p>
                        <p class="font-medium text-gray-800 dark:text-gray-100">{{ $purchase->date->format('d/m/Y') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30">
                        <p class="text-xs text-gray-400 mb-1">Supplier</p>
                        <p class="font-medium text-gray-800 dark:text-gray-100">{{ $purchase->supplier->name }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30">
                        <p class="text-xs text-gray-400 mb-1">Status Saat Ini</p>
                        <div>
                            @if ($purchase->isPending())
                            <span class="badge badge-info"><i class="ti ti-clock text-xs"></i> Pending</span>
                            @elseif ($purchase->isPartiallyReceived())
                            <span class="badge badge-warning"><i class="ti ti-clock text-xs"></i> Sebagian</span>
                            @elseif ($purchase->isReceived())
                            <span class="badge badge-success"><i class="ti ti-check text-xs"></i> Diterima</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipt Form -->
            <form action="{{ route('purchases.storeReceipt', $purchase) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                    <i class="ti ti-clipboard-check text-emerald-500"></i> Detail Penerimaan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label for="receipt_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nomor Penerimaan
                        </label>
                        <input type="text" name="receipt_number" id="receipt_number" required
                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 transition-colors"
                            value="{{ old('receipt_number', $receiptNumber) }}">
                    </div>

                    <div>
                        <label for="receipt_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Penerimaan
                        </label>
                        <input type="date" name="receipt_date" id="receipt_date" required
                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 transition-colors"
                            value="{{ old('receipt_date', date('Y-m-d')) }}">
                    </div>

                    <div>
                        <label for="receipt_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            File Bukti (Opsional)
                        </label>
                        <input type="file" name="receipt_file" id="receipt_file"
                            class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-300 transition-colors">
                    </div>
                </div>

                <!-- Items Table -->
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Dipesan</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sudah Diterima</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-64">Jumlah Diterima Sekarang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @foreach ($purchase->purchaseDetails as $detail)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800 dark:text-gray-100">{{ $detail->product->name }}</div>
                                    <input type="hidden" name="items[{{ $loop->index }}][purchase_detail_id]" value="{{ $detail->id }}">
                                    <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $detail->product_id }}">
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $detail->quantity }} {{ optional($detail->unit)->abbreviation }}
                                    @if ($detail->conversion_factor > 1)
                                    <span class="text-xs text-gray-400 block mt-0.5">
                                        ({{ $detail->base_quantity }} {{ optional($detail->product->baseUnit)->abbreviation ?? 'pcs' }})
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium text-emerald-600 dark:text-emerald-400">{{ $detail->received_quantity }}</span>
                                    {{ optional($detail->unit)->abbreviation }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="relative">
                                        <input type="number"
                                            name="items[{{ $loop->index }}][received_quantity]"
                                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-700 dark:text-gray-300"
                                            min="0"
                                            max="{{ $detail->quantity - $detail->received_quantity }}"
                                            value="{{ old('items.' . $loop->index . '.received_quantity', min($detail->quantity - $detail->received_quantity, $detail->quantity)) }}"
                                            onchange="updateBaseQuantity(this, {{ $detail->conversion_factor }}, '{{ optional($detail->product->baseUnit)->abbreviation ?? 'pcs' }}')"
                                            required>

                                        <input type="hidden" name="items[{{ $loop->index }}][conversion_factor]" value="{{ $detail->conversion_factor }}">

                                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ optional($detail->unit)->abbreviation }}</span>
                                            @if ($detail->conversion_factor > 1)
                                            <div class="text-emerald-600 dark:text-emerald-400 mt-1 base-quantity-display" id="base-quantity-{{ $loop->index }}">
                                                Setara dengan: {{ old('items.' . $loop->index . '.received_quantity', min($detail->quantity - $detail->received_quantity, $detail->quantity)) * $detail->conversion_factor }} {{ optional($detail->product->baseUnit)->abbreviation ?? 'pcs' }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700/50">
                                            <label class="block text-xs text-gray-500 mb-1">Tanggal Kadaluarsa (Opsional)</label>
                                            <input type="date" name="items[{{ $loop->index }}][expire_date]"
                                                class="w-full rounded-lg border-gray-200 dark:border-gray-600 text-xs focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-800 dark:text-gray-300">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-700 dark:text-gray-300"
                        placeholder="Tambahkan catatan penerimaan jika diperlukan...">{{ old('notes') }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                    <a href="{{ route('purchases.show', $purchase) }}"
                        class="btn-ghost">
                        <i class="ti ti-x text-base"></i> Batal
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="ti ti-check text-base"></i> Proses Penerimaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateBaseQuantity(input, conversionFactor, baseUnit) {
            const receivedQty = parseFloat(input.value) || 0;
            const baseQty = receivedQty * conversionFactor;

            const row = input.closest('tr');
            const baseQtyDisplay = row.querySelector('.base-quantity-display');

            if (baseQtyDisplay) {
                baseQtyDisplay.textContent = `Setara dengan: ${baseQty} ${baseUnit}`;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize base quantities
            document.querySelectorAll('input[name^="items"][name$="[received_quantity]"]').forEach((input, index) => {
                const row = input.closest('tr');
                const conversionFactorInput = row.querySelector('input[name^="items"][name$="[conversion_factor]"]');

                if (conversionFactorInput) {
                    const conversionFactor = parseFloat(conversionFactorInput.value) || 1;
                    if (conversionFactor > 1) {
                        // Find base unit from existing text content or standard 'pcs'
                        const baseUnitElement = document.getElementById(`base-quantity-${index}`);
                        const baseUnit = baseUnitElement ? baseUnitElement.textContent.trim().split(' ').pop() : 'pcs';
                        updateBaseQuantity(input, conversionFactor, baseUnit);
                    }
                }
            });

            // Validation
            document.querySelector('form').addEventListener('submit', function(e) {
                let hasReceivedItems = false;
                let totalReceived = 0;
                const inputs = document.querySelectorAll('input[name^="items"][name$="[received_quantity]"]');

                inputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    totalReceived += value;
                    if (value > 0) hasReceivedItems = true;
                });

                if (!hasReceivedItems) {
                    e.preventDefault();
                    alert('Setidaknya satu item harus diterima');
                    return false;
                }

                if (totalReceived > 1000) {
                    if (!confirm(`Anda akan menerima total ${totalReceived} item. Apakah jumlah ini benar?`)) {
                        e.preventDefault();
                        return false;
                    }
                }
            });

            // Max quantity validation
            document.querySelectorAll('input[name^="items"][name$="[received_quantity]"]').forEach(input => {
                input.addEventListener('change', function() {
                    const max = parseFloat(this.getAttribute('max'));
                    const value = parseFloat(this.value) || 0;

                    if (value > max) {
                        alert(`Jumlah yang diterima tidak boleh melebihi ${max}`);
                        this.value = max;
                    }
                    if (value < 0) this.value = 0;
                });
            });
        });
    </script>
    @endpush
</x-app-layout>