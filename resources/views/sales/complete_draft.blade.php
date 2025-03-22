<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Selesaikan Draft Transaksi') }}
                    </span>
                </h2>
                <p class="mt-1 text-sm text-gray-600">Selesaikan draft transaksi #{{ $sale->invoice_number }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Ada kesalahan dalam pengisian form</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-800">
                                    Anda hendak menyelesaikan transaksi draft. Pastikan semua informasi sudah benar
                                    sebelum proses. Stok produk akan dikurangi setelah transaksi selesai.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('sales.update', $sale) }}" method="POST" id="saleForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="complete_transaction" value="1">

                        <!-- Section: Informasi Penjualan -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <h3
                                class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Informasi Penjualan
                            </h3>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                                <div>
                                    <x-input-label for="invoice_number" value="Nomor Faktur" />
                                    <x-text-input id="invoice_number" name="invoice_number" type="text"
                                        class="mt-1 block w-full bg-gray-100" :value="$sale->invoice_number" readonly />
                                </div>

                                <div>
                                    <x-input-label for="date" value="Tanggal" />
                                    <x-text-input id="date" name="date" type="date" class="mt-1 block w-full"
                                        :value="date('Y-m-d', strtotime($sale->date))" readonly />
                                </div>

                                <div>
                                    <x-input-label for="customer" value="Nama Pelanggan" />
                                    <x-text-input id="customer" type="text" class="mt-1 block w-full bg-gray-100"
                                        value="{{ $sale->customer->nama ?? 'Pelanggan Umum' }}" readonly />
                                    <input type="hidden" name="customer_id" value="{{ $sale->customer_id }}">
                                </div>

                                <div>
                                    <x-input-label for="payment_method" value="Metode Pembayaran" />
                                    <select id="payment_method" name="payment_method"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                        <option value="cash" {{ $sale->payment_method === 'cash' ? 'selected' : '' }}>
                                            Tunai
                                        </option>
                                        <option value="transfer"
                                            {{ $sale->payment_method === 'transfer' ? 'selected' : '' }}>
                                            Transfer
                                        </option>
                                        <option value="credit"
                                            {{ $sale->payment_method === 'credit' ? 'selected' : '' }}>
                                            Hutang
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Item Penjualan -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mt-6">
                            <h3
                                class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Item Penjualan
                            </h3>

                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Produk</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Stok Saat Ini</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Jumlah</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Harga</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($sale->saleDetails as $detail)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div>
                                                            <div class="font-medium text-gray-900">
                                                                {{ $detail->product->name }}</div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ $detail->product->code }}</div>
                                                        </div>
                                                        <input type="hidden" name="product_id[]"
                                                            value="{{ $detail->product_id }}">
                                                        <input type="hidden" name="selling_price[]"
                                                            value="{{ $detail->selling_price }}">
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span
                                                        class="{{ $detail->product->stock < $detail->quantity ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                                        {{ $detail->product->stock }}
                                                    </span>

                                                    @if ($detail->product->stock < $detail->quantity)
                                                        <span
                                                            class="ml-2 inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                                            Tidak Cukup
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <input type="number" name="quantity[]"
                                                        value="{{ $detail->quantity }}"
                                                        class="w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        min="1" max="{{ $detail->product->stock }}"
                                                        onchange="calculateSubtotal(this)" required>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-700">Rp
                                                    {{ number_format($detail->selling_price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 subtotal text-sm font-medium text-gray-900">Rp
                                                    {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Total:</td>
                                            <td id="totalAmount" class="px-6 py-4 font-bold">Rp
                                                {{ number_format($sale->total_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Potongan:</td>
                                            <td class="px-6 py-4">
                                                <input type="number" name="discount" id="discount"
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    value="{{ $sale->discount }}" min="0"
                                                    onchange="calculateFinalTotal()">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Total Setelah
                                                Potongan:</td>
                                            <td id="finalAmount" class="px-6 py-4 font-bold">Rp
                                                {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr id="dp_container"
                                            style="{{ $sale->payment_method !== 'credit' ? 'display: none;' : '' }}">
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Uang Muka
                                                (DP):</td>
                                            <td class="px-6 py-4">
                                                <div class="flex space-x-2">
                                                    <input type="number" name="down_payment" id="down_payment"
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        value="{{ $sale->down_payment }}" min="0"
                                                        onchange="calculatePayment()">
                                                    <button type="button" onclick="setDownPayment(50)"
                                                        class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                                                        50%
                                                    </button>
                                                    <button type="button" onclick="setDownPayment(75)"
                                                        class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                                                        75%
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="paid_amount_container"
                                            style="{{ $sale->payment_method === 'credit' ? 'display: none;' : '' }}">
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Jumlah Yang
                                                Dibayar:</td>
                                            <td class="px-6 py-4">
                                                <div class="flex space-x-2">
                                                    <input type="number" name="paid_amount" id="paid_amount"
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        value="{{ $sale->paid_amount }}" min="0"
                                                        onchange="calculatePayment()" required>
                                                    <button type="button" onclick="setExactAmount()"
                                                        class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                                                        Uang Pas
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="remaining_container"
                                            style="{{ $sale->payment_method !== 'credit' ? 'display: none;' : '' }}">
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Sisa Hutang:
                                            </td>
                                            <td id="remainingAmount" class="px-6 py-4 font-bold">Rp 0</td>
                                        </tr>
                                        <tr id="change_container"
                                            style="{{ $sale->payment_method === 'credit' ? 'display: none;' : '' }}">
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Kembali:</td>
                                            <td id="changeAmount" class="px-6 py-4 font-bold">Rp 0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Section: Catatan -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mt-6">
                            <h3
                                class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Catatan
                            </h3>

                            <div>
                                <x-input-label for="notes" value="Catatan" />
                                <textarea id="notes" name="notes" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $sale->notes }}</textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('sales.drafts') }}"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                                Kembali
                            </a>
                            <button type="submit" id="submitButton"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                                Selesaikan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function calculateSubtotal(input) {
                const tr = input.closest('tr');
                const quantity = parseFloat(input.value) || 0;
                const priceText = tr.querySelector('td:nth-child(4)').textContent;
                const price = parseFloat(priceText.replace('Rp ', '').replace(/\./g, '')) || 0;
                const subtotal = quantity * price;

                const subtotalCell = tr.querySelector('.subtotal');
                subtotalCell.textContent = formatRupiah(subtotal);

                calculateTotal();
            }

            function calculateTotal() {
                const subtotals = document.querySelectorAll('.subtotal');
                let total = 0;

                subtotals.forEach(subtotal => {
                    const value = subtotal.textContent.replace('Rp ', '').replace(/\./g, '');
                    total += parseFloat(value) || 0;
                });

                document.getElementById('totalAmount').textContent = formatRupiah(total);
                calculateFinalTotal();
            }

            function calculateFinalTotal() {
                const totalText = document.getElementById('totalAmount').textContent;
                const total = parseFloat(totalText.replace('Rp ', '').replace(/\./g, '')) || 0;
                const discount = parseFloat(document.getElementById('discount').value) || 0;

                const finalTotal = Math.max(0, total - discount);
                document.getElementById('finalAmount').textContent = formatRupiah(finalTotal);

                calculatePayment();
            }

            function calculatePayment() {
                const finalText = document.getElementById('finalAmount').textContent;
                const finalTotal = parseFloat(finalText.replace('Rp ', '').replace(/\./g, '')) || 0;
                const paymentMethod = document.getElementById('payment_method').value;

                if (paymentMethod === 'credit') {
                    // Credit payment
                    const dp = parseFloat(document.getElementById('down_payment').value) || 0;
                    const remaining = Math.max(0, finalTotal - dp);

                    document.getElementById('remainingAmount').textContent = formatRupiah(remaining);
                    document.getElementById('paid_amount').value = dp;

                    // Validate DP
                    if (dp > finalTotal) {
                        alert('Uang muka tidak boleh melebihi total belanja');
                        document.getElementById('down_payment').value = finalTotal;
                        calculatePayment();
                    }
                } else {
                    // Cash/transfer payment
                    const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
                    const change = Math.max(0, paid - finalTotal);

                    document.getElementById('changeAmount').textContent = formatRupiah(change);

                    // Validate paid amount
                    validatePaidAmount(finalTotal, paid);
                }
            }

            function validatePaidAmount(total, paid) {
                const paymentMethod = document.getElementById('payment_method').value;
                const submitButton = document.getElementById('submitButton');

                if (paymentMethod !== 'credit' && paid < total) {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            function setExactAmount() {
                const finalText = document.getElementById('finalAmount').textContent;
                const finalTotal = parseFloat(finalText.replace('Rp ', '').replace(/\./g, '')) || 0;
                document.getElementById('paid_amount').value = finalTotal;
                calculatePayment();
            }

            function setDownPayment(percentage) {
                const finalText = document.getElementById('finalAmount').textContent;
                const finalTotal = parseFloat(finalText.replace('Rp ', '').replace(/\./g, '')) || 0;
                const downPaymentAmount = Math.round(finalTotal * percentage / 100);
                document.getElementById('down_payment').value = downPaymentAmount;
                calculatePayment();
            }

            function formatRupiah(number) {
                return 'Rp ' + number.toLocaleString('id-ID');
            }

            // Payment method change handler
            document.getElementById('payment_method').addEventListener('change', function() {
                const dpContainer = document.getElementById('dp_container');
                const remainingContainer = document.getElementById('remaining_container');
                const changeContainer = document.getElementById('change_container');
                const paidAmountContainer = document.getElementById('paid_amount_container');

                if (this.value === 'credit') {
                    // For credit payment
                    dpContainer.style.display = 'table-row';
                    remainingContainer.style.display = 'table-row';
                    paidAmountContainer.style.display = 'none';
                    changeContainer.style.display = 'none';

                    // Check if customer is selected
                    if (!document.querySelector('input[name="customer_id"]').value) {
                        alert('Untuk pembayaran hutang, pelanggan harus dipilih terlebih dahulu');
                    }
                } else {
                    // For cash/transfer
                    dpContainer.style.display = 'none';
                    remainingContainer.style.display = 'none';
                    paidAmountContainer.style.display = 'table-row';
                    changeContainer.style.display = 'table-row';

                    // Reset DP value
                    document.getElementById('down_payment').value = 0;
                }

                calculatePayment();
            });

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                calculateTotal();
            });

            // Form validation
            document.getElementById('saleForm').addEventListener('submit', function(e) {
                const paymentMethod = document.getElementById('payment_method').value;
                const finalText = document.getElementById('finalAmount').textContent;
                const finalTotal = parseFloat(finalText.replace('Rp ', '').replace(/\./g, '')) || 0;

                // Validate product quantities
                let hasStockError = false;
                document.querySelectorAll('input[name="quantity[]"]').forEach(input => {
                    const max = parseInt(input.getAttribute('max')) || 0;
                    const value = parseInt(input.value) || 0;

                    if (value > max) {
                        hasStockError = true;
                        input.classList.add('border-red-500', 'ring-red-500');
                    } else {
                        input.classList.remove('border-red-500', 'ring-red-500');
                    }
                });

                if (hasStockError) {
                    e.preventDefault();
                    alert('Beberapa produk memiliki jumlah yang melebihi stok yang tersedia. Silakan periksa kembali.');
                    return false;
                }

                // Validate payment (for non-credit)
                if (paymentMethod !== 'credit') {
                    const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
                    if (paid < finalTotal) {
                        e.preventDefault();
                        alert('Jumlah yang dibayar harus lebih besar atau sama dengan total belanja');
                        return false;
                    }
                }

                // Validate customer for credit payment
                if (paymentMethod === 'credit') {
                    const customerId = document.querySelector('input[name="customer_id"]').value;
                    if (!customerId) {
                        e.preventDefault();
                        alert('Transaksi kredit harus memilih pelanggan');
                        return false;
                    }
                }

                return true;
            });
        </script>
    @endpush
</x-app-layout>
