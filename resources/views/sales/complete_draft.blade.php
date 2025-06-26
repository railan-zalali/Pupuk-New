<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-indigo-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('Selesaikan Draft Transaksi') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Draft: {{ $sale->invoice_number }} - {{ $sale->created_at->format('d/m/Y H:i') }}
            </p>
        </div>

        <!-- Stock Issues Warning -->
        @if (count($stockIssues) > 0)
            <div class="rounded-lg bg-red-50 dark:bg-red-900/30 p-4 mb-6 border border-red-200 dark:border-red-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Stok tidak mencukupi untuk
                            beberapa produk:</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($stockIssues as $issue)
                                    <li>
                                        <strong>{{ $issue['product'] }}</strong>:
                                        Dibutuhkan {{ $issue['required'] }} {{ $issue['unit'] }},
                                        tersedia {{ $issue['available'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('sales.update', $sale) }}" method="POST" id="completeDraftForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="complete_transaction" value="1">

            <!-- Draft Details Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div
                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Draft</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <!-- Customer Info -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pelanggan</label>
                        <div class="mt-1">
                            @if ($sale->customer)
                                <p class="text-gray-900 dark:text-gray-100">{{ $sale->customer->nama }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $sale->customer->kecamatan_nama }}, {{ $sale->customer->kabupaten_nama }}</p>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 italic">Pelanggan Umum</p>
                            @endif
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Produk</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Jumlah</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Harga</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($sale->saleDetails as $detail)
                                    <tr>
                                        <td class="px-4 py-3">{{ $detail->product->name }}</td>
                                        <td class="px-4 py-3">{{ $detail->quantity }}
                                            {{ $detail->productUnit->unit->name ?? ($detail->productUnit->unit_name ?? 'N/A') }}
                                        </td>
                                        {{-- <td class="px-4 py-3">{{ $detail->quantity }}
                                            {{ $detail->productUnit->unit->name }}
                                            <td class="px-4 py-3">{{ $detail->quantity }} 
    {{ $detail->productUnit->unit->name ?? $detail->productUnit->unit_name ?? 'N/A' }}</td>
                                        </td> --}}
                                        <td class="px-4 py-3">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-medium">Total:</td>
                                    <td class="px-4 py-3 font-bold">Rp
                                        {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                @if ($sale->discount > 0)
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right font-medium">Diskon:</td>
                                        <td class="px-4 py-3">Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right font-medium">Total Akhir:</td>
                                        <td class="px-4 py-3 font-bold text-indigo-600 dark:text-indigo-400">
                                            Rp {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Details Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Pembayaran</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_method" name="payment_method" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                <option value="cash" {{ $sale->payment_method == 'cash' ? 'selected' : '' }}>Tunai
                                </option>
                                <option value="transfer" {{ $sale->payment_method == 'transfer' ? 'selected' : '' }}>
                                    Transfer</option>
                                <option value="credit" {{ $sale->payment_method == 'credit' ? 'selected' : '' }}>Kredit
                                </option>
                            </select>
                        </div>

                        <!-- Paid Amount (for cash/transfer) -->
                        <div id="paid_amount_container">
                            <label for="paid_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Jumlah Dibayar <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="paid_amount" name="paid_amount"
                                value="{{ $sale->total_amount - $sale->discount }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                min="0" required>
                        </div>

                        <!-- Down Payment (for credit) -->
                        <div id="down_payment_container" class="hidden">
                            <label for="down_payment"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Uang Muka (DP)
                            </label>
                            <input type="number" id="down_payment" name="down_payment" value="0"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                min="0">
                        </div>
                    </div>

                    <!-- Hidden inputs for existing data -->
                    @foreach ($sale->saleDetails as $index => $detail)
                        <input type="hidden" name="product_id[]" value="{{ $detail->product_id }}">
                        <input type="hidden" name="unit_id[]" value="{{ $detail->product_unit_id }}">
                        <input type="hidden" name="quantity[]" value="{{ $detail->quantity }}">
                        <input type="hidden" name="selling_price[]" value="{{ $detail->price }}">
                    @endforeach
                    <input type="hidden" name="customer_id" value="{{ $sale->customer_id }}">
                    <input type="hidden" name="discount" value="{{ $sale->discount }}">
                    <input type="hidden" name="notes" value="{{ $sale->notes }}">
                    <input type="hidden" name="vehicle_type" value="{{ $sale->vehicle_type }}">
                    <input type="hidden" name="vehicle_number" value="{{ $sale->vehicle_number }}">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('sales.drafts') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    Batal
                </a>
                @if (count($stockIssues) == 0)
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Selesaikan Transaksi
                    </button>
                @endif
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const paymentMethod = document.getElementById('payment_method');
                const paidAmountContainer = document.getElementById('paid_amount_container');
                const downPaymentContainer = document.getElementById('down_payment_container');

                function togglePaymentFields() {
                    if (paymentMethod.value === 'credit') {
                        paidAmountContainer.classList.add('hidden');
                        downPaymentContainer.classList.remove('hidden');
                    } else {
                        paidAmountContainer.classList.remove('hidden');
                        downPaymentContainer.classList.add('hidden');
                    }
                }

                paymentMethod.addEventListener('change', togglePaymentFields);
                togglePaymentFields();
            });
        </script>
    @endpush
</x-app-layout>
