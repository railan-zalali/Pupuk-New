<x-app-layout>
    <x-slot name="header">
        {{-- Header yang lebih terstruktur dan informatif --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-200 flex items-center">
                    <i class="ti ti-check-circle text-indigo-600 dark:text-indigo-400 text-2xl mr-3"></i>
                    <span>{{ __('Selesaikan Draft Transaksi') }}</span>
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Selesaikan detail untuk draft transaksi <span
                        class="font-medium text-indigo-700 dark:text-indigo-300">#{{ $sale->invoice_number }}</span>.
                </p>
            </div>
            <a href="{{ route('sales.drafts') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-150">
                <i class="ti ti-arrow-left mr-2"></i>
                Kembali ke Draft
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Pesan Error yang lebih jelas --}}
            @if ($errors->any())
                <div
                    class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-700/50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="ti ti-alert-circle text-red-400 dark:text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Terjadi Kesalahan</h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
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

            {{-- Peringatan Draft --}}
            <div
                class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 dark:border-yellow-600 p-4 mb-6 rounded-r-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="ti ti-alert-triangle text-yellow-500 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                            Anda sedang menyelesaikan transaksi draft. Pastikan semua informasi sudah benar. Stok produk
                            akan diperbarui setelah transaksi ini disimpan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form Utama --}}
            <form action="{{ route('sales.update', $sale) }}" method="POST" id="saleForm" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="complete_transaction" value="1">

                {{-- Section: Informasi Penjualan --}}
                <div class="content-card p-6 border dark:border-gray-700">
                    <h3
                        class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-3 border-b border-gray-200 dark:border-gray-600 flex items-center">
                        <i class="ti ti-file-invoice text-indigo-600 dark:text-indigo-400 mr-2"></i>
                        Informasi Penjualan
                    </h3>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-4">
                        <div>
                            <x-input-label for="invoice_number" value="Nomor Faktur" />
                            <x-text-input id="invoice_number" name="invoice_number" type="text"
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="$sale->invoice_number" readonly />
                        </div>
                        <div>
                            <x-input-label for="date" value="Tanggal" />
                            <x-text-input id="date" name="date" type="date"
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" :value="date('Y-m-d', strtotime($sale->date))" readonly />
                        </div>
                        <div>
                            <x-input-label for="customer" value="Nama Pelanggan" />
                            <x-text-input id="customer" type="text"
                                class="mt-1 block w-full bg-gray-100 dark:bg-gray-700"
                                value="{{ $sale->customer->nama ?? 'Pelanggan Umum' }}" readonly />
                            <input type="hidden" name="customer_id" id="customer_id" value="{{ $sale->customer_id }}">
                        </div>
                        <div>
                            <x-input-label for="payment_method" value="Metode Pembayaran" :required="true" />
                            <x-select-input id="payment_method" name="payment_method" class="mt-1 block w-full"
                                required>
                                <option value="cash" @selected($sale->payment_method === 'cash')>Tunai</option>
                                <option value="transfer" @selected($sale->payment_method === 'transfer')>Transfer</option>
                                <option value="credit" @selected($sale->payment_method === 'credit')>Hutang</option>
                            </x-select-input>
                            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Section: Item Penjualan --}}
                <div class="content-card p-6 border dark:border-gray-700">
                    <h3
                        class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-3 border-b border-gray-200 dark:border-gray-600 flex items-center">
                        <i class="ti ti-basket text-indigo-600 dark:text-indigo-400 mr-2"></i>
                        Item Penjualan
                    </h3>
                    <div class="mt-4 overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border dark:border-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Produk</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Stok Tersedia</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Harga</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @foreach ($sale->saleDetails as $detail)
                                    <tr
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150 product-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $detail->product->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $detail->product->code }}</div>
                                            <input type="hidden" name="product_id[]" value="{{ $detail->product_id }}">
                                            <input type="hidden" class="selling-price"
                                                value="{{ $detail->selling_price }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="stock-available text-sm font-medium {{ $detail->product->stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $detail->product->stock }}
                                            </span>
                                            <input type="hidden" class="max-stock"
                                                value="{{ $detail->product->stock }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- Input jumlah dengan validasi langsung --}}
                                            <x-text-input type="number" name="quantity[]" class="w-24 quantity-input"
                                                value="{{ $detail->quantity }}" min="1"
                                                max="{{ $detail->product->stock }}"
                                                oninput="handleQuantityChange(this)" required />
                                            <div class="text-xs text-red-600 dark:text-red-400 mt-1 stock-error-message"
                                                style="display: none;">Stok tidak cukup</div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-700 dark:text-gray-300 price-cell">
                                            {{ number_format($detail->selling_price, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-gray-100 subtotal-cell">
                                            {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- Pesan jika stok tidak cukup secara keseluruhan --}}
                        <div id="overall-stock-error" class="mt-3 text-sm text-red-600 dark:text-red-400"
                            style="display: none;">
                            <i class="ti ti-alert-circle mr-1"></i> Ada item dengan jumlah melebihi stok yang tersedia.
                            Periksa kembali item yang ditandai merah.
                        </div>
                    </div>
                </div>

                {{-- Section: Rincian Pembayaran & Catatan --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Kolom Kiri: Catatan --}}
                    <div class="lg:col-span-1 content-card p-6 border dark:border-gray-700 h-fit">
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-3 border-b border-gray-200 dark:border-gray-600 flex items-center">
                            <i class="ti ti-notebook text-indigo-600 dark:text-indigo-400 mr-2"></i> Catatan
                        </h3>
                        <x-input-label for="notes" value="Catatan Tambahan (Opsional)" />
                        <textarea id="notes" name="notes" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">{{ old('notes', $sale->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    {{-- Kolom Kanan: Ringkasan & Pembayaran --}}
                    <div class="lg:col-span-2 content-card p-6 border dark:border-gray-700">
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-3 border-b border-gray-200 dark:border-gray-600 flex items-center">
                            <i class="ti ti-receipt-2 text-indigo-600 dark:text-indigo-400 mr-2"></i> Ringkasan &
                            Pembayaran
                        </h3>
                        <div class="space-y-4">
                            {{-- Total & Diskon --}}
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Subtotal
                                    Item:</span>
                                <span id="totalAmount"
                                    class="text-sm font-semibold text-gray-900 dark:text-gray-100">Rp
                                    {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <label for="discount"
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Potongan
                                    (Rp):</label>
                                <x-text-input type="number" name="discount" id="discount" class="w-32 text-right"
                                    value="{{ old('discount', $sale->discount) }}" min="0"
                                    oninput="calculateFinalTotal()" />
                            </div>
                            <x-input-error :messages="$errors->get('discount')" class="mt-1 text-right" />

                            <hr class="border-gray-200 dark:border-gray-600">

                            <div class="flex justify-between items-center">
                                <span class="text-base font-semibold text-gray-800 dark:text-gray-200">Total
                                    Belanja:</span>
                                <span id="finalAmount"
                                    class="text-lg font-bold text-indigo-700 dark:text-indigo-300">Rp
                                    {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}</span>
                            </div>

                            <hr class="border-gray-200 dark:border-gray-600">

                            {{-- Input Pembayaran (conditional) --}}
                            {{-- Pembayaran Tunai / Transfer --}}
                            <div id="paid_amount_container" class="space-y-2"
                                style="{{ $sale->payment_method === 'credit' ? 'display: none;' : '' }}">
                                <div class="flex justify-between items-center">
                                    <label for="paid_amount"
                                        class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Jumlah
                                        Dibayar:</label>
                                    <div class="flex items-center gap-2">
                                        <x-primary-button type="button" onclick="setExactAmount()" size="sm"
                                            class="!py-1 !px-2">Uang Pas</x-primary-button>
                                        <x-text-input type="number" name="paid_amount" id="paid_amount"
                                            class="w-36 text-right"
                                            value="{{ old('paid_amount', $sale->paid_amount) }}" min="0"
                                            oninput="calculatePayment()" required />
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('paid_amount')" class="mt-1 text-right" />
                                <div id="paid_amount_error" class="text-xs text-red-600 dark:text-red-400 text-right"
                                    style="display: none;">Pembayaran kurang</div>
                            </div>

                            {{-- Pembayaran Hutang (DP) --}}
                            <div id="dp_container" class="space-y-2"
                                style="{{ $sale->payment_method !== 'credit' ? 'display: none;' : '' }}">
                                <div class="flex justify-between items-center">
                                    <label for="down_payment"
                                        class="text-sm font-medium text-gray-600 dark:text-gray-400 block">Uang Muka
                                        (DP):</label>
                                    <div class="flex items-center gap-2">
                                        <x-secondary-button type="button" onclick="setDownPayment(0)" size="sm"
                                            class="!py-1 !px-2">0%</x-secondary-button>
                                        <x-secondary-button type="button" onclick="setDownPayment(50)"
                                            size="sm" class="!py-1 !px-2">50%</x-secondary-button>
                                        <x-secondary-button type="button" onclick="setDownPayment(75)"
                                            size="sm" class="!py-1 !px-2">75%</x-secondary-button>
                                        <x-text-input type="number" name="down_payment" id="down_payment"
                                            class="w-36 text-right"
                                            value="{{ old('down_payment', $sale->down_payment) }}" min="0"
                                            oninput="calculatePayment()" />
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('down_payment')" class="mt-1 text-right" />
                                <div id="dp_error" class="text-xs text-red-600 dark:text-red-400 text-right"
                                    style="display: none;">DP melebihi total</div>
                            </div>

                            {{-- Hasil Kalkulasi (conditional) --}}
                            <div id="change_container"
                                class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-600"
                                style="{{ $sale->payment_method === 'credit' ? 'display: none;' : '' }}">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Kembali:</span>
                                <span id="changeAmount"
                                    class="text-sm font-semibold text-green-600 dark:text-green-400">Rp 0</span>
                            </div>
                            <div id="remaining_container"
                                class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-600"
                                style="{{ $sale->payment_method !== 'credit' ? 'display: none;' : '' }}">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Sisa Hutang:</span>
                                <span id="remainingAmount"
                                    class="text-sm font-semibold text-orange-600 dark:text-orange-400">Rp 0</span>
                            </div>
                            <div id="credit_customer_warning"
                                class="text-xs text-red-600 dark:text-red-400 text-right" style="display: none;">Pilih
                                pelanggan untuk transaksi Hutang.</div>

                        </div>
                    </div>
                </div>


                {{-- Form Actions --}}
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('sales.drafts') }}"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 focus:ring-offset-2 transition-colors duration-200">
                        Batal
                    </a>
                    <x-primary-button type="submit" id="submitButton">
                        <span id="submitButtonText">Selesaikan Transaksi</span>
                        <i id="submitButtonSpinner" class="ti ti-loader-2 animate-spin ml-2"
                            style="display: none;"></i>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // --- Helper Functions ---
            const formatRupiah = (number) => 'Rp ' + (number ? number.toLocaleString('id-ID') : '0');
            const parseRupiah = (rupiah) => parseFloat(rupiah.replace(/Rp\s?|(\.*)/g, '').replace(',', '.')) || 0;
            const getNumericValue = (elementId) => parseFloat(document.getElementById(elementId).value) || 0;
            const setElementText = (elementId, text) => document.getElementById(elementId).textContent = text;
            const showElement = (elementId) => document.getElementById(elementId).style.display = '';
            const hideElement = (elementId) => document.getElementById(elementId).style.display = 'none';
            const showTableRow = (elementId) => document.getElementById(elementId).style.display =
            'table-row'; // specific for table rows if needed
            const hideTableRow = (elementId) => document.getElementById(elementId).style.display = 'none';

            const submitButton = document.getElementById('submitButton');
            const submitButtonText = document.getElementById('submitButtonText');
            const submitButtonSpinner = document.getElementById('submitButtonSpinner');

            let isStockError = false; // Global flag for stock errors

            // --- Core Calculation Logic ---
            function handleQuantityChange(input) {
                const tr = input.closest('tr');
                const quantity = parseInt(input.value) || 0;
                const maxStock = parseInt(tr.querySelector('.max-stock').value) || 0;
                const price = parseRupiah(tr.querySelector('.price-cell').textContent);
                const subtotalCell = tr.querySelector('.subtotal-cell');
                const stockErrorMessage = tr.querySelector('.stock-error-message');

                // Validate stock and update UI
                if (quantity > maxStock) {
                    input.classList.add('border-red-500', 'dark:border-red-400', 'ring-red-500');
                    stockErrorMessage.style.display = 'block';
                } else {
                    input.classList.remove('border-red-500', 'dark:border-red-400', 'ring-red-500');
                    stockErrorMessage.style.display = 'none';
                }

                // Calculate and update subtotal
                const subtotal = quantity * price;
                subtotalCell.textContent = formatRupiah(subtotal);

                calculateTotal(); // Recalculate totals whenever quantity changes
            }

            function calculateTotal() {
                let total = 0;
                isStockError = false; // Reset stock error flag before check
                document.querySelectorAll('.product-row').forEach(tr => {
                    const subtotal = parseRupiah(tr.querySelector('.subtotal-cell').textContent);
                    total += subtotal;

                    // Check individual row stock error state
                    const quantityInput = tr.querySelector('.quantity-input');
                    const maxStock = parseInt(tr.querySelector('.max-stock').value) || 0;
                    if (parseInt(quantityInput.value) > maxStock) {
                        isStockError = true;
                    }
                });

                setElementText('totalAmount', formatRupiah(total));

                // Show/hide overall stock error message
                const overallStockError = document.getElementById('overall-stock-error');
                overallStockError.style.display = isStockError ? 'block' : 'none';

                calculateFinalTotal(); // Recalculate everything down the chain
            }

            function calculateFinalTotal() {
                const total = parseRupiah(document.getElementById('totalAmount').textContent);
                const discount = getNumericValue('discount');
                const finalTotal = Math.max(0, total - discount);

                setElementText('finalAmount', formatRupiah(finalTotal));
                calculatePayment(); // Recalculate payment details
            }

            function calculatePayment() {
                const finalTotal = parseRupiah(document.getElementById('finalAmount').textContent);
                const paymentMethod = document.getElementById('payment_method').value;
                const customerId = document.getElementById('customer_id').value;

                // Reset all payment-related errors first
                hideElement('paid_amount_error');
                hideElement('dp_error');
                hideElement('credit_customer_warning');
                document.getElementById('paid_amount').classList.remove('border-red-500', 'dark:border-red-400',
                'ring-red-500');
                document.getElementById('down_payment').classList.remove('border-red-500', 'dark:border-red-400',
                    'ring-red-500');

                let isPaymentValid = true; // Flag for overall payment validity

                if (paymentMethod === 'credit') {
                    // --- Credit Payment Logic ---
                    const dp = getNumericValue('down_payment');
                    const remaining = Math.max(0, finalTotal - dp);

                    setElementText('remainingAmount', formatRupiah(remaining));
                    document.getElementById('paid_amount').value = dp; // Sync paid amount for backend

                    // Validate DP
                    if (dp > finalTotal) {
                        showElement('dp_error');
                        document.getElementById('down_payment').classList.add('border-red-500', 'dark:border-red-400',
                            'ring-red-500');
                        isPaymentValid = false;
                    }
                    // Validate if customer is selected for credit
                    if (!customerId) {
                        showElement('credit_customer_warning');
                        isPaymentValid = false;
                    }

                } else {
                    // --- Cash/Transfer Payment Logic ---
                    const paid = getNumericValue('paid_amount');
                    const change = Math.max(0, paid - finalTotal);

                    setElementText('changeAmount', formatRupiah(change));

                    // Validate paid amount (must be >= final total)
                    if (paid < finalTotal) {
                        showElement('paid_amount_error');
                        document.getElementById('paid_amount').classList.add('border-red-500', 'dark:border-red-400',
                            'ring-red-500');
                        isPaymentValid = false;
                    }
                }
                validateFormSubmission(isPaymentValid); // Update submit button state
            }

            // --- UI Interaction Functions ---
            function setExactAmount() {
                const finalTotal = parseRupiah(document.getElementById('finalAmount').textContent);
                document.getElementById('paid_amount').value = finalTotal;
                calculatePayment(); // Recalculate change etc.
            }

            function setDownPayment(percentage) {
                const finalTotal = parseRupiah(document.getElementById('finalAmount').textContent);
                const downPaymentAmount = Math.round((finalTotal * percentage) / 100);
                document.getElementById('down_payment').value = downPaymentAmount;
                calculatePayment(); // Recalculate remaining etc.
            }

            // --- Event Listeners ---
            // Payment method change handler
            document.getElementById('payment_method').addEventListener('change', function() {
                const isCredit = this.value === 'credit';
                const customerId = document.getElementById('customer_id').value;

                // Toggle visibility of payment sections
                hideElement('dp_container');
                hideElement('remaining_container');
                hideElement('paid_amount_container');
                hideElement('change_container');
                hideElement('credit_customer_warning'); // Hide initially

                if (isCredit) {
                    showElement('dp_container');
                    showElement('remaining_container');
                    if (!customerId) {
                        showElement('credit_customer_warning'); // Show warning if no customer
                    }
                } else {
                    showElement('paid_amount_container');
                    showElement('change_container');
                    // Reset DP value when switching away from credit
                    document.getElementById('down_payment').value = 0;
                }
                calculatePayment(); // Recalculate based on new method
            });

            // --- Form Validation & Submission ---
            function validateFormSubmission(isPaymentValid) {
                // Disable submit if there's a stock error OR payment is invalid
                const shouldDisable = isStockError || !isPaymentValid;
                submitButton.disabled = shouldDisable;
                submitButton.classList.toggle('opacity-50', shouldDisable);
                submitButton.classList.toggle('cursor-not-allowed', shouldDisable);
            }

            document.getElementById('saleForm').addEventListener('submit', function(e) {
                // Final validation check before submitting
                calculateTotal(); // Ensure totals and stock checks are up-to-date
                calculatePayment(); // Ensure payment validation is up-to-date

                if (isStockError || !validatePaymentOnSubmit()) {
                    e.preventDefault(); // Prevent submission
                    alert('Harap perbaiki kesalahan pada form sebelum melanjutkan.');
                    return false;
                }

                // Show loading state on submit button
                submitButton.disabled = true;
                submitButtonText.style.display = 'none';
                submitButtonSpinner.style.display = 'inline-block';
            });

            // Helper for final payment validation on submit
            function validatePaymentOnSubmit() {
                const paymentMethod = document.getElementById('payment_method').value;
                const finalTotal = parseRupiah(document.getElementById('finalAmount').textContent);
                const customerId = document.getElementById('customer_id').value;

                if (paymentMethod === 'credit') {
                    const dp = getNumericValue('down_payment');
                    if (dp > finalTotal) return false; // DP cannot exceed total
                    if (!customerId) return false; // Customer must be selected for credit
                } else {
                    const paid = getNumericValue('paid_amount');
                    if (paid < finalTotal) return false; // Paid amount must be >= total
                }
                return true; // Payment is valid for submission
            }


            // --- Initialization ---
            document.addEventListener('DOMContentLoaded', function() {
                calculateTotal(); // Initial calculation on page load
                // Trigger change event manually to set initial visibility of payment sections
                document.getElementById('payment_method').dispatchEvent(new Event('change'));
            });
        </script>
    @endpush
</x-app-layout>
