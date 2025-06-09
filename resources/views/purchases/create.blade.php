<x-app-layout>
    <div class="space-y-6">
        <!-- Page Heading -->
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                <span class="inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Buat Pembelian Baru') }}
                </span>
            </h2>
        </div>

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 dark:bg-red-900/50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Ada kesalahan dalam pengisian
                            form:</h3>
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

        <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
            @csrf

            <!-- Section: Informasi Pembelian -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3
                    class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Informasi Pembelian
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-input-label for="invoice_number" value="Nomor Faktur" />
                        <x-text-input id="invoice_number" name="invoice_number" type="text"
                            class="mt-1 block w-full bg-gray-50 dark:bg-gray-700" :value="$invoiceNumber" readonly />
                    </div>

                    <div>
                        <x-input-label for="date" value="Tanggal" />
                        <x-text-input id="date" name="date" type="date" class="mt-1 block w-full"
                            :value="old('date', date('Y-m-d'))" required />
                    </div>

                    <div>
                        <x-input-label for="due_date" value="Tanggal Jatuh Tempo" />
                        <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full"
                            :value="old('due_date', date('Y-m-d', strtotime('+30 days')))" required />
                    </div>

                    <div>
                        <x-input-label for="supplier_id" value="Supplier" />
                        <select id="supplier_id" name="supplier_id"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            <option value="">Pilih Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="reference_number" value="Nomor Referensi" />
                        <x-text-input id="reference_number" name="reference_number" type="text"
                            class="mt-1 block w-full" :value="old('reference_number')" />
                    </div>
                </div>
            </div>

            <!-- Section: Item Pembelian -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Item Pembelian
                    </h3>
                    <div class="flex space-x-2">
                        <button type="button" onclick="addItem()"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Item
                        </button>
                    </div>
                </div>

                <!-- Search Product Container -->
                <div class="mb-4 relative add-item-container">
                    <div class="flex items-center space-x-2">
                        <div class="relative flex-grow">
                            <input type="text" id="product-search"
                                placeholder="Cari produk berdasarkan nama atau kode..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300 pl-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <button type="button" id="scan-barcode"
                            class="inline-flex items-center rounded-md bg-gray-200 dark:bg-gray-700 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            Scan Barcode
                        </button>
                    </div>
                    <!-- Dropdown untuk hasil pencarian produk -->
                    <div
                        class="search-results absolute z-10 w-full mt-1 rounded-md bg-white dark:bg-gray-700 shadow-lg max-h-60 overflow-auto hidden">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Produk
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Harga
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Subtotal
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody id="purchaseItems"
                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-4 text-right font-medium text-gray-500 dark:text-gray-400">Total:
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-gray-100" id="totalAmount">Rp 0
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Section: Catatan -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 mt-6">
                <h3
                    class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Catatan
                </h3>

                <textarea id="notes" name="notes" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                    placeholder="Tambahkan catatan transaksi jika diperlukan...">{{ old('notes') }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="window.history.back()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                    Batal
                </button>
                <button type="submit" id="submit-btn"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 border border-transparent rounded-md shadow-sm">
                    Proses Pembelian
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function createItemRow() {
                return `
        <tr>
            <td class="px-6 py-4">
                <select name="product_id[]" required class="product-select w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300" onchange="updateProductDetails(this)">
                    <option value="">Pilih Produk</option>
                </select>
                <div class="product-info mt-1 text-xs"></div>
            </td>
            <td class="px-6 py-4">
                <div class="flex space-x-2">
                    <input type="number" name="quantity[]" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300"
                        value="1" min="1" oninput="calculateSubtotal(this)">
                    <select name="unit_id[]" required class="unit-select w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300" onchange="updateConversionFactor(this)">
                        <option value="">Unit</option>
                    </select>
                    <input type="hidden" name="conversion_factor[]" value="1">
                </div>
                <div class="conv-info mt-1 text-xs"></div>
            </td>
            <td class="px-6 py-4">
                <input type="number" name="purchase_price[]" required
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-gray-300"
                    value="0" min="0" step="1" oninput="calculateSubtotal(this)">
                <div class="price-info mt-1 text-xs"></div>
            </td>
            <td class="px-6 py-4 subtotal text-gray-900 dark:text-gray-300">Rp 0</td>
            <td class="px-6 py-4">
                <button type="button" onclick="removeItem(this)"
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                    Hapus
                </button>
            </td>
        </tr>
        `;
            }

            function calculateSubtotal(input) {
                const tr = input.closest('tr');
                const quantity = parseFloat(tr.querySelector('input[name="quantity[]"]').value) || 0;
                const price = parseFloat(tr.querySelector('input[name="purchase_price[]"]').value) || 0;

                const subtotal = quantity * price;
                tr.querySelector('.subtotal').textContent = formatRupiah(subtotal);
                calculateTotal();
            }

            function calculateTotal() {
                const subtotals = document.querySelectorAll('.subtotal');
                let total = 0;

                subtotals.forEach(subtotal => {
                    const value = subtotal.textContent.replace('Rp ', '').replace(/\./g, '').replace(/,/g, '');
                    const numValue = parseFloat(value) || 0;
                    total += numValue;
                });

                document.getElementById('totalAmount').textContent = formatRupiah(total);
            }

            function formatRupiah(number) {
                return 'Rp ' + Math.round(number).toLocaleString('id-ID');
            }

            function updateProductOptions(selectElement = null) {
                const supplierId = document.getElementById('supplier_id').value;

                if (!supplierId) {
                    document.querySelectorAll('select[name="product_id[]"]').forEach(select => {
                        select.innerHTML = '<option value="">Pilih Produk</option>';
                        select.closest('tr').querySelector('.product-info').innerHTML = '';
                    });
                    return;
                }

                // Show loading state
                if (selectElement) {
                    selectElement.innerHTML = '<option value="">Loading...</option>';
                    selectElement.disabled = true;
                } else {
                    document.querySelectorAll('select[name="product_id[]"]').forEach(select => {
                        select.innerHTML = '<option value="">Loading...</option>';
                        select.disabled = true;
                    });
                }

                fetch(`/purchases/products-by-supplier/${supplierId}`)
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        return response.json();
                    })
                    .then(products => {
                        let optionsHtml = '<option value="">Pilih Produk</option>';
                        products.forEach(product => {
                            const price = product.purchase_price || 0;
                            const stockClass = product.stock < product.min_stock ? 'text-red-600' : '';
                            optionsHtml +=
                                `<option value="${product.id}" data-price="${price}" data-stock="${product.stock}" data-min-stock="${product.min_stock}" class="${stockClass}">${product.name}</option>`;
                        });

                        if (selectElement) {
                            selectElement.innerHTML = optionsHtml;
                            selectElement.disabled = false;
                        } else {
                            document.querySelectorAll('select[name="product_id[]"]').forEach(select => {
                                select.innerHTML = optionsHtml;
                                select.disabled = false;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                        alert('Gagal memuat daftar produk. Silakan coba lagi.');

                        // Reset selects
                        if (selectElement) {
                            selectElement.innerHTML = '<option value="">Pilih Produk</option>';
                            selectElement.disabled = false;
                        } else {
                            document.querySelectorAll('select[name="product_id[]"]').forEach(select => {
                                select.innerHTML = '<option value="">Pilih Produk</option>';
                                select.disabled = false;
                            });
                        }
                    });
            }

            function updateConversionFactor(select) {
                const tr = select.closest('tr');
                const conversionInput = tr.querySelector('input[name="conversion_factor[]"]');
                const priceInput = tr.querySelector('input[name="purchase_price[]"]');
                const productSelect = tr.querySelector('select[name="product_id[]"]');
                const selectedOption = select.options[select.selectedIndex];

                if (selectedOption && selectedOption.value) {
                    // Update faktor konversi
                    if (selectedOption.dataset.conversion) {
                        conversionInput.value = selectedOption.dataset.conversion;

                        // Update info konversi
                        const factor = parseFloat(selectedOption.dataset.conversion);
                        if (factor > 1) {
                            tr.querySelector('.conv-info').innerHTML =
                                `<span class="text-blue-500">1 ${selectedOption.text} = ${factor} unit dasar</span>`;
                        } else {
                            tr.querySelector('.conv-info').innerHTML = '';
                        }
                    } else {
                        conversionInput.value = 1;
                        tr.querySelector('.conv-info').innerHTML = '';
                    }

                    // Update harga berdasarkan unit yang dipilih
                    if (selectedOption.dataset.price) {
                        priceInput.value = selectedOption.dataset.price;
                    }

                    // Update stock display if product is selected
                    if (productSelect.value) {
                        const baseStock = parseFloat(selectedOption.dataset.baseStock) || 0;
                        const minStock = parseFloat(tr.dataset.minStock) || 0;
                        updateStockDisplay(baseStock, minStock, select);
                    }
                } else {
                    conversionInput.value = 1;
                    tr.querySelector('.conv-info').innerHTML = '';
                }

                // Recalculate subtotal when unit changes
                calculateSubtotal(priceInput);
            }

            function removeItem(button) {
                const tbody = document.getElementById('purchaseItems');
                if (tbody.children.length > 1) {
                    button.closest('tr').remove();
                    calculateTotal();
                } else {
                    alert('Minimal satu item harus ada');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Inisialisasi halaman dengan item pertama dan listener
                addItem();
                initializeEventListeners();
                initProductSearch();

                // Auto-fokus ke supplier saat halaman dimuat
                document.getElementById('supplier_id').focus();
            });

            function initializeEventListeners() {
                // Listener untuk supplier
                const supplierSelect = document.getElementById('supplier_id');
                supplierSelect.addEventListener('change', function() {
                    updateProductOptions();

                    // Reset form items jika supplier berubah
                    if (document.querySelectorAll('#purchaseItems tr').length > 1) {
                        if (confirm('Mengganti supplier akan mengosongkan semua item. Lanjutkan?')) {
                            clearAllItems();
                            addItem();
                        } else {
                            // Kembalikan ke supplier sebelumnya
                            this.value = this.getAttribute('data-last-value');
                            return;
                        }
                    }

                    // Simpan nilai supplier saat ini
                    this.setAttribute('data-last-value', this.value);
                });

                // Delegate event untuk tabel item
                document.getElementById('purchaseItems').addEventListener('input', function(e) {
                    if (e.target.name === 'purchase_price[]' || e.target.name === 'quantity[]') {
                        calculateSubtotal(e.target);
                        validateNumber(e.target);
                    }
                });

                // Event listener untuk tanggal jatuh tempo
                const dateInput = document.getElementById('date');
                const dueDateInput = document.getElementById('due_date');

                dateInput.addEventListener('change', function() {
                    // Set tanggal jatuh tempo secara default 30 hari dari tanggal pembelian
                    const date = new Date(this.value);
                    date.setDate(date.getDate() + 30);
                    dueDateInput.value = date.toISOString().split('T')[0];

                    // Set min date untuk jatuh tempo
                    dueDateInput.min = this.value;
                });

                // Form validation sebelum submit
                document.getElementById('purchaseForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (validateForm()) {
                        // Show loading state
                        const submitBtn = document.getElementById('submit-btn');
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;

                        this.submit();
                    }
                });

                // Listener untuk tombol scan barcode
                document.getElementById('scan-barcode').addEventListener('click', function() {
                    const barcodeInput = prompt('Scan atau masukkan barcode produk:');
                    if (barcodeInput) {
                        processBarcode(barcodeInput);
                    }
                });
            }

            function processBarcode(barcode) {
                // Check if supplier is selected
                const supplierId = document.getElementById('supplier_id').value;
                if (!supplierId) {
                    alert('Pilih supplier terlebih dahulu');
                    return;
                }

                // Show loading indicator
                const searchResults = document.querySelector('.search-results');
                searchResults.classList.remove('hidden');
                searchResults.innerHTML = '<div class="p-4 text-gray-500">Mencari produk...</div>';

                // Find product by barcode
                fetch(`/products/find-by-barcode?barcode=${barcode}&supplier_id=${supplierId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Produk tidak ditemukan');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Check if product+unit combination already exists
                            const existingProduct = findExistingProduct(data.product.id, data.unit.id);

                            if (existingProduct) {
                                // Increment quantity
                                const qtyInput = existingProduct.querySelector('input[name="quantity[]"]');
                                qtyInput.value = parseFloat(qtyInput.value) + 1;
                                calculateSubtotal(qtyInput);

                                // Highlight the row
                                highlightRow(existingProduct);
                            } else {
                                // Add new row
                                addItem();
                                const newRow = document.querySelector('#purchaseItems tr:last-child');
                                const productSelect = newRow.querySelector('select[name="product_id[]"]');

                                // Wait for options to load
                                setTimeout(() => {
                                    productSelect.value = data.product.id;
                                    productSelect.dispatchEvent(new Event('change'));

                                    // Wait for units to load, then set unit
                                    setTimeout(() => {
                                        const unitSelect = newRow.querySelector('select[name="unit_id[]"]');
                                        if (data.unit && data.unit.id) {
                                            unitSelect.value = data.unit.id;
                                            unitSelect.dispatchEvent(new Event('change'));
                                        }
                                        highlightRow(newRow);
                                    }, 300);
                                }, 300);
                            }

                            searchResults.innerHTML = '';
                            searchResults.classList.add('hidden');
                        } else {
                            searchResults.innerHTML =
                                `<div class="p-4 text-red-500">${data.message || 'Produk tidak ditemukan'}</div>`;
                        }
                    })
                    .catch(error => {
                        searchResults.innerHTML = `<div class="p-4 text-red-500">Error: ${error.message}</div>`;
                        setTimeout(() => {
                            searchResults.innerHTML = '';
                            searchResults.classList.add('hidden');
                        }, 3000);
                    });
            }

            function highlightRow(row) {
                // Remove previous highlights
                document.querySelectorAll('tr.highlight').forEach(r => r.classList.remove('highlight', 'bg-yellow-50',
                    'dark:bg-yellow-900/20'));

                // Add highlight
                row.classList.add('highlight', 'bg-yellow-50', 'dark:bg-yellow-900/20');

                // Scroll to row
                row.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                // Remove highlight after delay
                setTimeout(() => {
                    row.classList.remove('highlight', 'bg-yellow-50', 'dark:bg-yellow-900/20');
                }, 2000);
            }

            function findExistingProduct(productId, unitId = null) {
                let foundRow = null;

                // Jika unit ID diberikan, cari kombinasi produk+unit
                if (unitId) {
                    document.querySelectorAll('#purchaseItems tr').forEach(row => {
                        const productSelect = row.querySelector('select[name="product_id[]"]');
                        const unitSelect = row.querySelector('select[name="unit_id[]"]');

                        if (productSelect && unitSelect &&
                            productSelect.value == productId &&
                            unitSelect.value == unitId) {
                            foundRow = row;
                        }
                    });
                    return foundRow;
                }

                // Jika unit ID tidak diberikan, cari hanya berdasarkan produk (untuk scan barcode)
                document.querySelectorAll('select[name="product_id[]"]').forEach(select => {
                    if (select.value == productId) {
                        foundRow = select.closest('tr');
                    }
                });
                return foundRow;
            }

            function validateForm() {
                const supplier = document.getElementById('supplier_id');
                const date = document.getElementById('date');
                const dueDate = document.getElementById('due_date');
                const productSelects = document.querySelectorAll('select[name="product_id[]"]');
                const unitSelects = document.querySelectorAll('select[name="unit_id[]"]');

                // Reset semua error
                document.querySelectorAll('.border-red-500').forEach(el => {
                    el.classList.remove('border-red-500');
                });

                let valid = true;
                let errorMessages = [];

                // Validasi supplier
                if (!supplier.value) {
                    supplier.classList.add('border-red-500');
                    errorMessages.push('Silakan pilih supplier');
                    valid = false;
                }

                // Validasi tanggal
                if (!date.value) {
                    date.classList.add('border-red-500');
                    errorMessages.push('Tanggal pembelian wajib diisi');
                    valid = false;
                }

                if (!dueDate.value) {
                    dueDate.classList.add('border-red-500');
                    errorMessages.push('Tanggal jatuh tempo wajib diisi');
                    valid = false;
                }

                // Validate due date is after or equal to purchase date
                if (date.value && dueDate.value && new Date(dueDate.value) < new Date(date.value)) {
                    dueDate.classList.add('border-red-500');
                    errorMessages.push('Tanggal jatuh tempo harus sama atau setelah tanggal pembelian');
                    valid = false;
                }

                // Validasi minimal satu item pembelian
                if (productSelects.length === 0) {
                    errorMessages.push('Minimal satu item pembelian harus diisi');
                    valid = false;
                }

                // Check for duplicate products with same unit
                const productUnitPairs = [];
                let hasDuplicates = false;

                productSelects.forEach((select, index) => {
                    if (select.value) {
                        const unitSelect = unitSelects[index];
                        if (unitSelect.value) {
                            const pair = select.value + '-' + unitSelect.value;

                            if (productUnitPairs.includes(pair)) {
                                select.classList.add('border-red-500');
                                unitSelect.classList.add('border-red-500');
                                hasDuplicates = true;
                            } else {
                                productUnitPairs.push(pair);
                            }
                        }
                    }
                });

                if (hasDuplicates) {
                    errorMessages.push(
                        'Terdapat kombinasi produk dan unit yang sama. Silakan gabungkan jumlahnya atau gunakan unit yang berbeda.'
                    );
                    valid = false;
                }

                // Validasi setiap item pembelian
                productSelects.forEach((product, index) => {
                    if (!product.value) {
                        product.classList.add('border-red-500');
                        valid = false;
                    }

                    if (!unitSelects[index].value) {
                        unitSelects[index].classList.add('border-red-500');
                        valid = false;
                    }

                    const qty = document.querySelectorAll('input[name="quantity[]"]')[index];
                    const price = document.querySelectorAll('input[name="purchase_price[]"]')[index];

                    if (!qty.value || parseFloat(qty.value) <= 0) {
                        qty.classList.add('border-red-500');
                        valid = false;
                    }

                    if (!price.value || parseFloat(price.value) <= 0) {
                        price.classList.add('border-red-500');
                        valid = false;
                    }
                });

                // Tampilkan error jika ada
                if (!valid && errorMessages.length > 0) {
                    alert('Mohon perbaiki kesalahan berikut:\n- ' + errorMessages.join('\n- '));
                }

                return valid;
            }

            function validateNumber(input) {
                const value = parseFloat(input.value);
                if (isNaN(value) || value < 0) {
                    input.value = 0;
                    calculateSubtotal(input);
                }
            }

            function clearAllItems() {
                const tbody = document.getElementById('purchaseItems');
                while (tbody.firstChild) {
                    tbody.removeChild(tbody.firstChild);
                }
            }

            function addItem() {
                const tbody = document.getElementById('purchaseItems');
                const itemRow = createItemRow();

                tbody.insertAdjacentHTML('beforeend', itemRow);
                const newRow = tbody.lastElementChild;

                // Initialize product options
                const productSelect = newRow.querySelector('select[name="product_id[]"]');
                updateProductOptions(productSelect);

                // Add focus
                productSelect.focus();

                // Re-calculate totals
                calculateTotal();
            }

            function updateProductDetails(select) {
                const tr = select.closest('tr');
                const priceInput = tr.querySelector('input[name="purchase_price[]"]');
                const unitSelect = tr.querySelector('select[name="unit_id[]"]');
                const conversionInput = tr.querySelector('input[name="conversion_factor[]"]');
                const productInfoDiv = tr.querySelector('.product-info');
                const productId = select.value;

                // Reset dan disable inputs saat loading
                priceInput.value = 0;
                priceInput.disabled = true;
                unitSelect.innerHTML = '<option value="">Loading...</option>';
                unitSelect.disabled = true;
                conversionInput.value = 1;
                productInfoDiv.innerHTML = '';

                // Reset jika tidak ada produk yang dipilih
                if (!productId) {
                    priceInput.value = 0;
                    priceInput.disabled = false;
                    unitSelect.innerHTML = '<option value="">Unit</option>';
                    unitSelect.disabled = false;
                    calculateSubtotal(priceInput);
                    return;
                }

                // Ambil harga dari opsi yang dipilih untuk sementara
                const selectedOption = select.options[select.selectedIndex];
                const defaultPrice = parseFloat(selectedOption?.dataset.price) || 0;
                priceInput.value = defaultPrice;
                calculateSubtotal(priceInput);

                // Show stock status
                const stock = parseInt(selectedOption?.dataset.stock) || 0;
                const minStock = parseInt(selectedOption?.dataset.minStock) || 0;

                if (stock <= minStock) {
                    productInfoDiv.innerHTML = `<span class="text-red-500">Stok rendah: ${stock} tersisa</span>`;
                } else {
                    productInfoDiv.innerHTML = `<span class="text-green-500">Stok: ${stock}</span>`;
                }

                // Store product data for stock calculations
                tr.dataset.baseStock = stock;
                tr.dataset.minStock = minStock;

                // Fetch product units dan detail lainnya
                fetch(`/purchases/product-units/${productId}`)
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        // Update harga dengan data pembelian terakhir jika ada
                        if (data.last_purchase && data.last_purchase.price) {
                            priceInput.value = parseFloat(data.last_purchase.price);

                            // Tampilkan tooltip informasi harga terakhir
                            const priceInfoContainer = tr.querySelector('.price-info');
                            priceInfoContainer.innerHTML = `
                    <span class="text-gray-500">
                        Pembelian terakhir: ${formatRupiah(data.last_purchase.price)}
                        (${data.last_purchase.date})
                    </span>
                `;
                        }

                        // Update unit dropdown
                        let unitOptions = '<option value="">Unit</option>';
                        if (data.units && data.units.length > 0) {
                            data.units.forEach(unit => {
                                unitOptions += `<option value="${unit.id}"
                    data-conversion="${unit.pivot.conversion_factor}"
                    data-price="${unit.pivot.purchase_price}"
                    data-base-stock="${data.stock}">
                    ${unit.name}
                </option>`;
                            });
                        }
                        unitSelect.innerHTML = unitOptions;

                        // Update product info with more details
                        if (data.has_low_stock) {
                            productInfoDiv.innerHTML = `
                    <span class="text-red-500">
                        Stok rendah: ${data.stock} tersisa
                        (Min: ${data.min_stock})
                    </span>
                `;
                        } else {
                            productInfoDiv.innerHTML = `
                    <span class="text-green-500">
                        Stok: ${data.stock}
                    </span>
                `;
                        }

                        // Select default unit jika ada
                        if (data.units && data.units.length > 0) {
                            const defaultUnit = data.units.find(u => u.pivot.is_default === 1);
                            if (defaultUnit) {
                                unitSelect.value = defaultUnit.id;
                                conversionInput.value = defaultUnit.pivot.conversion_factor;

                                // Tambahkan informasi konversi jika bukan unit dasar
                                if (defaultUnit.pivot.conversion_factor > 1) {
                                    tr.querySelector('.conv-info').innerHTML = `
                            <span class="text-blue-500">
                                1 ${defaultUnit.name} = ${defaultUnit.pivot.conversion_factor} ${data.default_unit}
                            </span>
                        `;
                                } else {
                                    tr.querySelector('.conv-info').innerHTML = '';
                                }
                            }
                        }

                        // Enable inputs kembali
                        unitSelect.disabled = false;
                        priceInput.disabled = false;

                        // Setup event listener for unit change
                        unitSelect.addEventListener('change', function() {
                            updateStockDisplay(data.stock, data.min_stock, this);
                        });

                        // Initialize stock display for default unit
                        updateStockDisplay(data.stock, data.min_stock, unitSelect);

                        // Recalculate subtotal
                        calculateSubtotal(priceInput);
                    })
                    .catch(error => {
                        console.error('Error fetching product details:', error);
                        // Fallback
                        unitSelect.innerHTML = '<option value="">Unit</option>';
                        unitSelect.disabled = false;
                        priceInput.disabled = false;
                        productInfoDiv.innerHTML = `<span class="text-red-500">Error: ${error.message}</span>`;
                    });
            }

            function updateStockDisplay(baseStock, minStock, unitSelect) {
                const tr = unitSelect.closest('tr');
                const productInfoDiv = tr.querySelector('.product-info');
                const selectedOption = unitSelect.options[unitSelect.selectedIndex];

                if (!selectedOption || !selectedOption.value) {
                    return;
                }

                const conversionFactor = parseFloat(selectedOption.dataset.conversion) || 1;

                // Hitung stok dalam unit yang dipilih
                const stockInSelectedUnit = Math.floor(baseStock / conversionFactor);
                const minStockInSelectedUnit = Math.ceil(minStock / conversionFactor);

                // Update tampilan stok
                if (stockInSelectedUnit <= minStockInSelectedUnit) {
                    productInfoDiv.innerHTML = `
            <span class="text-red-500">
                Stok rendah: ${stockInSelectedUnit} ${selectedOption.text} tersisa
                (Min: ${minStockInSelectedUnit})
            </span>
        `;
                } else {
                    productInfoDiv.innerHTML = `
            <span class="text-green-500">
                Stok: ${stockInSelectedUnit} ${selectedOption.text}
            </span>
        `;
                }
            }

            function initProductSearch() {
                const searchInput = document.getElementById('product-search');
                const searchResults = document.querySelector('.search-results');
                let searchTimeout;

                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);

                    const query = this.value.trim();
                    if (query.length < 2) {
                        searchResults.innerHTML = '';
                        searchResults.classList.add('hidden');
                        return;
                    }

                    const supplierId = document.getElementById('supplier_id').value;
                    if (!supplierId) {
                        searchResults.innerHTML = '<div class="p-4 text-red-500">Pilih supplier terlebih dahulu</div>';
                        searchResults.classList.remove('hidden');
                        return;
                    }

                    // Show loading
                    searchResults.innerHTML = '<div class="p-4 text-gray-500">Mencari produk...</div>';
                    searchResults.classList.remove('hidden');

                    searchTimeout = setTimeout(() => {
                        fetchSearchResults(query, supplierId);
                    }, 300);
                });

                // Close search results when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.classList.add('hidden');
                    }
                });
            }

            function fetchSearchResults(query, supplierId) {
                const searchResults = document.querySelector('.search-results');

                fetch(`/purchases/search-products?query=${encodeURIComponent(query)}&supplier_id=${supplierId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal memuat hasil pencarian');
                        return response.json();
                    })
                    .then(products => {
                        if (products.length === 0) {
                            searchResults.innerHTML = '<div class="p-4 text-gray-500">Tidak ada produk ditemukan</div>';
                            return;
                        }

                        let resultsHTML = '';
                        products.forEach(product => {
                            const stockClass = product.is_low_stock ? 'text-red-500' : 'text-green-500';
                            resultsHTML += `
                        <div class="product-result p-3 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer border-b border-gray-200 dark:border-gray-700"
                            data-id="${product.id}" data-name="${product.name}" data-price="${product.purchase_price || 0}">
                            <div class="font-medium">${product.name}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Kode: ${product.code || '-'} • ${product.category_name || 'Tanpa Kategori'}
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="${stockClass} text-xs">
                                    Stok: ${product.stock} ${product.default_unit || 'pcs'}
                                </span>
                                <span class="text-xs font-medium">
                                    ${formatRupiah(product.purchase_price || 0)}
                                </span>
                            </div>
                        </div>
                    `;
                        });

                        searchResults.innerHTML = resultsHTML;

                        // Add click event to results
                        document.querySelectorAll('.product-result').forEach(item => {
                            item.addEventListener('click', function() {
                                const productId = this.dataset.id;
                                addProductFromSearch(productId);

                                // Clear search
                                document.getElementById('product-search').value = '';
                                searchResults.classList.add('hidden');
                            });
                        });
                    })
                    .catch(error => {
                        searchResults.innerHTML = `<div class="p-4 text-red-500">Error: ${error.message}</div>`;
                    });
            }

            function addProductFromSearch(productId) {
                // Add new row with this product
                addItem();
                const newRow = document.querySelector('#purchaseItems tr:last-child');
                const productSelect = newRow.querySelector('select[name="product_id[]"]');

                // Wait for options to load
                setTimeout(() => {
                    productSelect.value = productId;
                    productSelect.dispatchEvent(new Event('change'));
                    highlightRow(newRow);
                }, 300);
            }
        </script>
    @endpush
</x-app-layout>
