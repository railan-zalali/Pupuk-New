<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2
                        class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-indigo-600 dark:text-indigo-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Selesaikan Draft Transaksi') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Lengkapi informasi pembayaran untuk menyelesaikan draft #{{ $sale->invoice_number }}
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('sales.drafts') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Draft
                    </a>
                </div>
            </div>
        </div>

        <!-- Stock Issues Alert -->
        @if (!empty($stockIssues))
            <div class="rounded-lg bg-red-50 dark:bg-red-900/30 p-4 mb-6 border border-red-200 dark:border-red-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Stok tidak mencukupi!</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($stockIssues as $issue)
                                    <li>{{ $issue['product'] }}: Dibutuhkan {{ $issue['required'] }}
                                        {{ $issue['unit'] }}, tersedia {{ $issue['available'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <p class="mt-2 text-sm text-red-700 dark:text-red-300">Silakan edit draft terlebih dahulu untuk
                            menyesuaikan jumlah produk.</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('sales.update', $sale) }}" method="POST" id="completeDraftForm" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="complete_transaction" value="1">

            <!-- Customer & Draft Info -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white">Informasi Draft</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No.
                                Invoice</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $sale->invoice_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pelanggan</label>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                                {{ $sale->customer ? $sale->customer->nama : 'Pelanggan Umum' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Summary -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white">Ringkasan Item</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                                        Produk</th>
                                    <th
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                                        Jumlah</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                                        Harga</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($sale->saleDetails as $detail)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $detail->product->name }}</td>
                                        <td class="px-3 py-3 text-sm text-center text-gray-900 dark:text-gray-100">
                                            {{ $detail->quantity }} {{ $detail->productUnit->unit->abbreviation }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($detail->price, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm text-right font-medium text-gray-900 dark:text-gray-100">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>

                                    <!-- Hidden inputs for items -->
                                    <input type="hidden" name="product_id[]" value="{{ $detail->product_id }}">
                                    <input type="hidden" name="unit_id[]" value="{{ $detail->product_unit_id }}">
                                    <input type="hidden" name="quantity[]" value="{{ $detail->quantity }}">
                                    <input type="hidden" name="selling_price[]" value="{{ $detail->price }}">
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="3"
                                        class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Total:</td>
                                    <td class="px-4 py-3 text-right text-lg font-bold text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if ($sale->discount > 0)
                                    <tr>
                                        <td colspan="3"
                                            class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Diskon:</td>
                                        <td
                                            class="px-4 py-3 text-right text-lg font-medium text-red-600 dark:text-red-400">
                                            - Rp {{ number_format($sale->discount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"
                                            class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Total Akhir:</td>
                                        <td
                                            class="px-4 py-3 text-right text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                            Rp {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900 dark:text-white">Informasi Pembayaran</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="payment_method" value="Metode Pembayaran"
                                class="font-medium text-gray-700 dark:text-gray-300" />
                            <select id="payment_method" name="payment_method"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                required>
                                <option value="cash">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="credit">Kredit</option>
                            </select>
                        </div>

                        <!-- Cash/Transfer Payment -->
                        <div id="regular-payment" class="space-y-4">
                            <div>
                                <x-input-label for="paid_amount" value="Jumlah Dibayar"
                                    class="font-medium text-gray-700 dark:text-gray-300" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="paid_amount" id="paid_amount"
                                        class="pl-10 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                        value="{{ $sale->total_amount - $sale->discount }}" min="0" required>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kembalian: <span
                                        id="change_amount" class="font-medium text-gray-900 dark:text-gray-100">Rp
                                        0</span></p>
                            </div>
                        </div>

                        <!-- Credit Payment -->
                        <div id="credit-payment" class="space-y-4 hidden">
                            <div>
                                <x-input-label for="down_payment" value="Uang Muka (DP)"
                                    class="font-medium text-gray-700 dark:text-gray-300" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" name="down_payment" id="down_payment"
                                        class="pl-10 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                        value="0" min="0">
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Sisa Hutang: <span
                                        id="remaining_amount" class="font-medium text-red-600 dark:text-red-400">Rp
                                        {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" name="customer_id" value="{{ $sale->customer_id }}">
                    <input type="hidden" name="discount" value="{{ $sale->discount }}">
                    <input type="hidden" name="notes" value="{{ $sale->notes }}">
                    <input type="hidden" name="vehicle_type" value="{{ $sale->vehicle_type }}">
                    <input type="hidden" name="vehicle_number" value="{{ $sale->vehicle_number }}">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('sales.drafts') }}"
                    class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Batal
                </a>
                <button type="submit" @if (!empty($stockIssues)) disabled @endif
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white {{ empty($stockIssues) ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-400 cursor-not-allowed' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Selesaikan Transaksi
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const finalTotal = {{ $sale->total_amount - $sale->discount }};

                // Handle payment method change
                $('#payment_method').on('change', function() {
                    const method = $(this).val();
                    if (method === 'credit') {
                        $('#regular-payment').addClass('hidden');
                        $('#credit-payment').removeClass('hidden');
                        $('#paid_amount').prop('required', false);
                        calculateRemaining();
                    } else {
                        $('#regular-payment').removeClass('hidden');
                        $('#credit-payment').addClass('hidden');
                        $('#paid_amount').prop('required', true);
                        calculateChange();
                    }
                });

                // Calculate change for cash/transfer
                $('#paid_amount').on('input', calculateChange);

                // Calculate remaining for credit
                $('#down_payment').on('input', calculateRemaining);

                function calculateChange() {
                    const paid = parseFloat($('#paid_amount').val()) || 0;
                    const change = paid - finalTotal;
                    $('#change_amount').text('Rp ' + formatNumber(Math.max(0, change)));
                }

                function calculateRemaining() {
                    const dp = parseFloat($('#down_payment').val()) || 0;
                    const remaining = finalTotal - dp;
                    $('#remaining_amount').text('Rp ' + formatNumber(Math.max(0, remaining)));
                }

                function formatNumber(num) {
                    return num.toLocaleString('id-ID');
                }

                // Form validation
                $('#completeDraftForm').on('submit', function(e) {
                    const method = $('#payment_method').val();

                    if (method !== 'credit') {
                        const paid = parseFloat($('#paid_amount').val()) || 0;
                        if (paid < finalTotal) {
                            e.preventDefault();
                            alert('Jumlah pembayaran tidak mencukupi!');
                            return false;
                        }
                    }

                    // Show loading state
                    const submitBtn = $('button[type="submit"]');
                    submitBtn.prop('disabled', true)
                        .html(
                            '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...'
                            );
                });
            });
        </script>
    @endpush
</x-app-layout>
