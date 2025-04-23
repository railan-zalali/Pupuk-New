<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Catat Penerimaan Pembelian #{{ $purchase->invoice_number }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ \Carbon\Carbon::parse($purchase->date)->format('d F Y, H:i') }}
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 dark:bg-red-900/50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Terdapat beberapa kesalahan:</h3>
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

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <!-- Purchase Information -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Informasi Pembelian
                </h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. Invoice</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $purchase->invoice_number }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $purchase->date->format('d/m/Y') }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $purchase->supplier->name }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1">
                            @if ($purchase->isPending())
                                <span
                                    class="inline-flex items-center rounded-full bg-yellow-100 dark:bg-yellow-900/50 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:text-yellow-200">
                                    Pending
                                </span>
                            @elseif ($purchase->isPartiallyReceived())
                                <span
                                    class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/50 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:text-blue-200">
                                    Diterima Sebagian
                                </span>
                            @elseif ($purchase->isReceived())
                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/50 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-200">
                                    Diterima
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Form for receipt submission -->
<form action="{{ route('purchases.storeReceipt', $purchase) }}" method="POST" enctype="multipart/form-data" class="mt-6">
    @csrf
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Detail Penerimaan
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label for="receipt_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nomor Penerimaan
                </label>
                <input type="text" name="receipt_number" id="receipt_number" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                    value="{{ old('receipt_number', 'RCV-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)) }}">
            </div>

            <div>
                <label for="receipt_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Tanggal Penerimaan
                </label>
                <input type="date" name="receipt_date" id="receipt_date" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                    value="{{ old('receipt_date', date('Y-m-d')) }}">
            </div>

            <div>
                <label for="receipt_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    File Bukti Penerimaan (Opsional)
                </label>
                <input type="file" name="receipt_file" id="receipt_file"
                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300">
            </div>
        </div>

        <!-- Items to receive -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Produk
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jumlah Dipesan
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Sudah Diterima
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jumlah Diterima
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($purchase->purchaseDetails as $detail)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $detail->product->name }}
                                <input type="hidden" name="items[{{ $loop->index }}][purchase_detail_id]"
                                    value="{{ $detail->id }}">
                                <input type="hidden" name="items[{{ $loop->index }}][product_id]"
                                    value="{{ $detail->product_id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $detail->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $detail->received_quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="number" name="items[{{ $loop->index }}][received_quantity]"
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                                    min="0" max="{{ $detail->quantity - $detail->received_quantity }}"
                                    value="{{ old('items.' . $loop->index . '.received_quantity', min($detail->quantity - $detail->received_quantity, $detail->quantity)) }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Catatan (Opsional)
            </label>
            <textarea name="notes" id="notes" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                placeholder="Tambahkan catatan penerimaan jika diperlukan...">{{ old('notes') }}</textarea>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('purchases.show', $purchase) }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                Batal
            </a>
            <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 border border-transparent rounded-md shadow-sm">
                Proses Penerimaan
            </button>
        </div>
    </div>
</form>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Validasi form sebelum submit
                document.querySelector('form').addEventListener('submit', function(e) {
    let hasReceivedItems = false;
    const inputs = document.querySelectorAll('input[name^="items"][name$="[received_quantity]"]');

    inputs.forEach(input => {
        if (parseInt(input.value) > 0) {
            hasReceivedItems = true;
        }
    });

    if (!hasReceivedItems) {
        e.preventDefault();
        alert('Setidaknya satu item harus diterima');
        return false;
    }
});

                // Validasi input quantity
                document.querySelectorAll('input[name^="received_quantity"]').forEach(input => {
                    input.addEventListener('change', function() {
                        const max = parseInt(this.getAttribute('max'));
                        const value = parseInt(this.value) || 0;

                        if (value > max) {
                            alert(`Jumlah yang diterima tidak boleh melebihi ${max}`);
                            this.value = max;
                        }
                        if (value < 0) {
                            this.value = 0;
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
