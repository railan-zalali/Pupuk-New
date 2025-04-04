<?php
if (!function_exists('formatRupiah')) {
    function formatRupiah($number)
    {
        return 'Rp ' . number_format(round($number), 0, ',', '.');
    }
}
?>
<x-app-layout>
    <!-- Header yang diperbarui untuk sales.create.blade.php -->
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('Buat Penjualan Baru') }}
                    </span>
                </h2>
                <p class="mt-1 text-sm text-gray-600">Kelola semua transaksi penjualan Anda dalam satu tempat</p>
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
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
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

            @push('styles')
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            @endpush

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                        @csrf

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
                                        class="mt-1 block w-full bg-gray-100" :value="$invoiceNumber" readonly />
                                </div>

                                <div>
                                    <x-input-label for="date" value="Tanggal" />
                                    <x-text-input id="date" name="date" type="date" class="mt-1 block w-full"
                                        :value="old('date', date('Y-m-d'))" required />
                                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="customer_select" value="Nama Pelanggan" />
                                    <select id="customer_select" name="customer_id"
                                        class="select2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">-- Pilih Pelanggan atau Ketik Nama Baru --</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->nama }} -
                                                {{ $customer->nik }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="customer_name" id="new_customer_name">
                                    <div class="mt-1 text-xs text-gray-500">Pilih pelanggan yang ada atau ketik nama
                                        baru</div>
                                </div>

                                <div>
                                    <x-input-label for="payment_method" value="Metode Pembayaran" />
                                    <select id="payment_method" name="payment_method"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                        <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>
                                            Tunai
                                        </option>
                                        <option value="transfer"
                                            {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>
                                            Transfer
                                        </option>
                                        <option value="credit"
                                            {{ old('payment_method') === 'credit' ? 'selected' : '' }}>
                                            Hutang
                                        </option>
                                    </select>
                                    <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="vehicle_type" value="Jenis Kendaraan" />
                                    <select id="vehicle_type" name="vehicle_type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="Truk">Truk</option>
                                        <option value="Pickup">Pickup</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="vehicle_number" value="Nomor Kendaraan" />
                                    <x-text-input id="vehicle_number" name="vehicle_number" type="text"
                                        class="mt-1 block w-full" />
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

                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Item Penjualan</h3>
                                <button type="button" onclick="addItem()"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    Tambah Item
                                </button>
                            </div>

                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Produk</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Stok</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Jumlah</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Harga</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Subtotal</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="saleItems" class="divide-y divide-gray-200 bg-white">
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Total:</td>
                                            <td id="totalAmount" class="px-6 py-4 font-bold">{{ formatRupiah(0) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr id="dp_container" style="display: none;">
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Uang Muka
                                                (DP):</td>
                                            <td class="px-6 py-4">
                                                <div class="flex space-x-2">
                                                    <input type="number" name="down_payment" id="down_payment"
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        value="0" min="0"
                                                        onchange="calculateRemainingAmount()">
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
                                            <td></td>
                                        </tr>
                                        <tr id="paid_amount_container">
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Jumlah Yang
                                                Dibayar:</td>
                                            <td class="px-6 py-4">
                                                <div class="flex space-x-2">
                                                    <input type="number" name="paid_amount" id="paid_amount"
                                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        value="0" min="0" onchange="calculateChange()"
                                                        required>
                                                    <button type="button" onclick="setExactAmount()"
                                                        class="inline-flex items-center rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                                                        Uang Pas
                                                    </button>
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr id="discount_container">
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Potongan:</td>
                                            <td class="px-6 py-4">
                                                <input type="number" name="discount" id="discount"
                                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    value="0" min="0" onchange="calculateFinalTotal()">
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Total Setelah
                                                Potongan:</td>
                                            <td id="finalAmount" class="px-6 py-4 font-bold">Rp 0</td>
                                            <td></td>
                                        </tr>
                                        <tr id="remaining_container" style="display: none;">
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Sisa Hutang:
                                            </td>
                                            <td id="remainingAmount" class="px-6 py-4 font-bold">Rp 0</td>
                                            <td></td>
                                        </tr>
                                        <tr id="change_container">
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Kembali:</td>
                                            <td id="changeAmount" class="px-6 py-4 font-bold">Rp 0</td>
                                            <td></td>
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
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" onclick="window.history.back()"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                                Batal
                            </button>
                            <button type="submit" name="save_as_draft" value="1"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                                Simpan sebagai Draft
                            </button>
                            <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                                Proses Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function createItemRow() {
                return `
                            <tr>
                                <td class="px-6 py-4">
                                    <select name="product_id[]" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="updatePrice(this)">
                                        <option value="">Pilih Produk</option>
                                        @foreach ($products as $product)
    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->stock }}">
                                                {{ $product->name }}
                                            </option>
    @endforeach
                                    </select>
                                </td>
                                <td class="px-6 py-4 available-stock">0</td>
                                <td class="px-6 py-4">
                                    <input type="number" name="quantity[]" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="1" min="1" onchange="calculateSubtotal(this)">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" name="selling_price[]" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="0" min="0" onkeypress="return event.charCode >= 48 && event.charCode <= 57 onchange="calculateSubtotal(this)">
                                </td>
                                <td class="px-6 py-4 subtotal">Rp 0</td>
                                <td class="px-6 py-4">
                                    <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                        `;
            }

            function updatePrice(select) {
                const tr = select.closest('tr');
                const priceInput = tr.querySelector('input[name="selling_price[]"]');
                const stockDisplay = tr.querySelector('.available-stock');
                const quantityInput = tr.querySelector('input[name="quantity[]"]');
                const selectedOption = select.options[select.selectedIndex];

                if (selectedOption.value) {
                    const price = selectedOption.dataset.price;
                    const stock = selectedOption.dataset.stock;
                    priceInput.value = price || 0; // Set nilai default tapi tetap bisa diubah
                    stockDisplay.textContent = stock;
                    quantityInput.max = stock;
                } else {
                    priceInput.value = 0;
                    stockDisplay.textContent = 0;
                    quantityInput.max = 0;
                }

                calculateSubtotal(priceInput);
            }

            function calculateSubtotal(input) {
                const tr = input.closest('tr');
                const quantity = Math.round(tr.querySelector('input[name="quantity[]"]').value || 0);
                const price = Math.round(tr.querySelector('input[name="selling_price[]"]').value || 0);
                const subtotal = quantity * price;
                tr.querySelector('.subtotal').textContent = formatRupiah(subtotal);
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

                // Panggil calculateFinalTotal untuk menerapkan diskon
                calculateFinalTotal();
            }

            function calculateFinalTotal() {
                const totalText = document.getElementById('totalAmount').textContent;
                const total = parseFloat(totalText.replace('Rp ', '').replace(/\./g, '')) || 0;
                const discount = parseFloat(document.getElementById('discount').value) || 0;

                // Hitung total akhir setelah diskon
                const finalTotal = Math.max(0, total - discount);
                document.getElementById('finalAmount').textContent = formatRupiah(finalTotal);

                // Perbarui perhitungan pembayaran berdasarkan metode pembayaran
                const paymentMethod = document.getElementById('payment_method').value;
                if (paymentMethod === 'credit') {
                    calculateRemainingAmount();
                } else {
                    calculateChange();
                }
            }

            function setExactAmount() {
                const finalText = document.getElementById('finalAmount').textContent;
                const finalTotal = parseFloat(finalText.replace('Rp ', '').replace(/\./g, '')) || 0;
                document.getElementById('paid_amount').value = finalTotal;
                calculateChange();
            }

            function calculateChange() {
                const totalText = document.getElementById('totalAmount').textContent;
                const total = parseFloat(totalText.replace('Rp ', '').replace(/\./g, '')) || 0;
                const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
                const change = paid - total;
                document.getElementById('changeAmount').textContent = formatRupiah(Math.max(0, change));

                // Validasi jumlah yang dibayar minimum HANYA untuk pembayaran tunai/transfer
                const submitButton = document.querySelector('button[type="submit"]');
                const paymentMethod = document.getElementById('payment_method').value;

                if (paymentMethod !== 'credit' && paid < total) {
                    // Nonaktifkan tombol submit hanya untuk transaksi non-kredit
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            function calculateRemainingAmount() {
                const totalText = document.getElementById('finalAmount').textContent; // Changed from totalAmount to finalAmount
                const total = parseFloat(totalText.replace('Rp ', '').replace(/\./g, '')) || 0;
                const dp = parseFloat(document.getElementById('down_payment').value) || 0;
                const remaining = total - dp;

                document.getElementById('remainingAmount').textContent = formatRupiah(Math.max(0, remaining));

                // Update hidden paid_amount field for backend processing
                document.getElementById('paid_amount').value = dp;
                const dpValue = parseFloat(document.getElementById('down_payment').value) || 0;
                document.getElementById('paid_amount').value = dpValue;

                // Validasi: DP tidak boleh melebihi total
                if (dp > total) {
                    alert('Uang muka tidak boleh melebihi total belanja');
                    document.getElementById('down_payment').value = total;
                    calculateRemainingAmount();
                }
            }

            function setDownPayment(percentage) {
                const finalText = document.getElementById('finalAmount').textContent;
                const finalTotal = parseFloat(finalText.replace('Rp ', '').replace(/\./g, '')) || 0;
                const downPaymentAmount = Math.round(finalTotal * percentage / 100);
                document.getElementById('down_payment').value = downPaymentAmount;
                calculateRemainingAmount();
            }

            function formatRupiah(number) {
                // Hilangkan decimal & format dengan separator ribuan
                return 'Rp ' + Math.round(number).toLocaleString('id-ID', {
                    maximumFractionDigits: 0,
                    currencyDisplay: 'symbol'
                });
            }

            function addItem() {
                const tbody = document.getElementById('saleItems');
                tbody.insertAdjacentHTML('beforeend', createItemRow());
            }

            function removeItem(button) {
                const tbody = document.getElementById('saleItems');
                if (tbody.children.length > 1) {
                    button.closest('tr').remove();
                    calculateTotal();
                }
            }

            // Add event listener for payment method change
            document.getElementById('payment_method').addEventListener('change', function() {
                const dpContainer = document.getElementById('dp_container');
                const remainingContainer = document.getElementById('remaining_container');
                const changeContainer = document.getElementById('change_container');
                const paidAmountContainer = document.getElementById('paid_amount_container');
                const paidAmountInput = document.getElementById('paid_amount');
                const dpInput = document.getElementById('down_payment');
                const submitButton = document.querySelector('button[type="submit"]');

                if (this.value === 'credit') {
                    // Untuk pembayaran kredit, aktifkan tombol submit terlepas dari jumlah yang dibayar
                    dpInput.name = 'down_payment';
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');

                    // Tampilkan field DP dan sisa hutang
                    dpContainer.style.display = 'table-row';
                    remainingContainer.style.display = 'table-row';

                    // Untuk kredit dengan DP, sembunyikan jumlah yang dibayar reguler dan kembalian
                    paidAmountContainer.style.display = 'none';
                    changeContainer.style.display = 'none';

                    // Reset nilai
                    dpInput.value = 0;
                    paidAmountInput.value = 0;

                    // Periksa apakah pelanggan dipilih
                    if (!document.getElementById('customer_select').value) {
                        alert('Untuk pembayaran hutang, pilih pelanggan terlebih dahulu');
                    }

                    // Hitung sisa hutang
                    calculateRemainingAmount();

                } else {
                    // Sembunyikan field DP dan sisa hutang untuk tunai/transfer
                    dpContainer.style.display = 'none';
                    remainingContainer.style.display = 'none';

                    // Tampilkan jumlah yang dibayar dan kembalian untuk tunai/transfer
                    paidAmountContainer.style.display = 'table-row';
                    changeContainer.style.display = 'table-row';

                    // Reset nilai DP
                    dpInput.value = 0;

                    // Hitung kembalian untuk tunai/transfer
                    calculateChange();
                }
            });

            // Validasi form sebelum submit
            document.getElementById('saleForm').addEventListener('submit', function(e) {
                const tbody = document.getElementById('saleItems');
                if (tbody.children.length === 0) {
                    e.preventDefault();
                    alert('Tambahkan setidaknya satu item');
                    return false;
                }

                const paymentMethod = document.getElementById('payment_method').value;
                const totalText = document.getElementById('totalAmount').textContent;
                const total = parseFloat(totalText.replace('Rp ', '').replace(/\./g, '')) || 0;
                const paid = parseFloat(document.getElementById('paid_amount').value) || 0;

                // Validasi jumlah yang dibayar untuk metode pembayaran non-kredit
                if (paymentMethod !== 'credit' && paid < total) {
                    e.preventDefault();
                    alert('Jumlah yang dibayar harus lebih besar atau sama dengan total belanja');
                    return false;
                }

                // Validasi pemilihan pelanggan untuk pembayaran kredit
                if (paymentMethod === 'credit') {
                    if (!document.getElementById('customer_select').value) {
                        e.preventDefault();
                        alert('Transaksi kredit harus memilih pelanggan');
                        return false;
                    }
                }

                return true;
            });

            // Inisialisasi Select2
            $('#customer_select').select2({
                tags: true,
                createTag: function(params) {
                    return {
                        id: 'new:' + params.term,
                        text: params.term,
                        newTag: true
                    }
                },
                templateResult: function(data) {
                    var $result = $("<span></span>");

                    if (data.newTag) {
                        $result.text(data.text + " (Tambahkan Customer Baru)");
                        $result.addClass("text-blue-600");
                    } else {
                        $result.text(data.text);
                    }

                    return $result;
                }
            });

            // Tangani pengiriman form untuk memisahkan customer_id vs customer_name
            $('#saleForm').on('submit', function() {
                var customerSelect = $('#customer_select');
                var selectedOption = customerSelect.val();

                // Jika nilai yang dipilih dimulai dengan 'new:', itu adalah nama pelanggan baru
                if (selectedOption && selectedOption.startsWith('new:')) {
                    // Ekstrak bagian nama
                    var newName = selectedOption.substring(4);

                    // Setel nama pelanggan baru di input tersembunyi
                    $('#new_customer_name').val(newName);

                    // Reset customer_id menjadi kosong karena kita membuat pelanggan baru
                    customerSelect.val('');
                }

                return true;
            });

            // Tambahkan item pertama saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
                addItem();
            });
            // Handle form submission to separate customer_id vs customer_name
            $('#saleForm').on('submit', function() {
                var customerSelect = $('#customer_select');
                var selectedOption = customerSelect.val();

                // If the selected value starts with 'new:', it's a new customer name
                if (selectedOption && selectedOption.startsWith('new:')) {
                    // Extract the name part
                    var newName = selectedOption.substring(4);

                    // Set the new customer name in the hidden input
                    $('#new_customer_name').val(newName);

                    // Reset the customer_id to empty since we're creating a new customer
                    customerSelect.val('');
                }

                return true;
            });
        </script>
    @endpush
</x-app-layout>
