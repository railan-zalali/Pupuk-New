<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create New Purchase') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
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
                    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div>
                                <x-input-label for="invoice_number" value="Invoice Number" />
                                <x-text-input id="invoice_number" name="invoice_number" type="text"
                                    class="mt-1 block w-full" :value="$invoiceNumber" readonly />
                            </div>

                            <div>
                                <x-input-label for="date" value="Date" />
                                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full"
                                    :value="old('date', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="supplier_id" value="Supplier" />
                                <select id="supplier_id" name="supplier_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Purchase Items</h3>
                                <button type="button" onclick="addItem()"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    Add Item
                                </button>
                            </div>

                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Product</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Quantity</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Purchase Price</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Subtotal</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="purchaseItems" class="divide-y divide-gray-200 bg-white">
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                            <td class="px-6 py-4 font-bold" id="totalAmount">Rp 0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="notes" value="Notes" />
                            <textarea id="notes" name="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <x-secondary-button type="button" onclick="window.history.back()">
                                Cancel
                            </x-secondary-button>
                            <x-primary-button>
                                Create Purchase
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function createItemRow() {
            return `
                <tr>
                    <td class="px-6 py-4">
                        <select name="product_id[]" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="updatePrice(this)">
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="quantity[]" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="1" min="1" onchange="calculateSubtotal(this)">
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="purchase_price[]" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="0" min="0" onchange="calculateSubtotal(this)">
                    </td>
                    <td class="px-6 py-4 subtotal">Rp 0</td>
                    <td class="px-6 py-4">
                        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-900">Remove</button>
                    </td>
                </tr>
            `;
        }

        function updatePrice(select) {
            const tr = select.closest('tr');
            const priceInput = tr.querySelector('input[name="purchase_price[]"]');
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.dataset.price;
            priceInput.value = price || 0;
            calculateSubtotal(priceInput);
        }

        function calculateSubtotal(input) {
            const tr = input.closest('tr');
            const quantity = tr.querySelector('input[name="quantity[]"]').value || 0;
            const price = tr.querySelector('input[name="purchase_price[]"]').value || 0;
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
        }

        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function addItem() {
            const tbody = document.getElementById('purchaseItems');
            tbody.insertAdjacentHTML('beforeend', createItemRow());
        }

        function removeItem(button) {
            const tbody = document.getElementById('purchaseItems');
            if (tbody.children.length > 1) {
                button.closest('tr').remove();
                calculateTotal();
            }
        }

        // Add first item on page load
        document.addEventListener('DOMContentLoaded', function() {
            addItem();
        });
    </script>
</x-app-layout>
