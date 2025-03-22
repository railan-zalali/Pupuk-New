<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Invoice Details') }} - {{ $sale->invoice_number }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('sales.invoice', $sale) }}" target="_blank"
                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Print Invoice
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg print-container">
                <div class="p-6 text-gray-900">
                    <!-- Company Info (Visible when printing) -->
                    <div class="hidden print:block mb-8 text-center">
                        <h1 class="text-2xl font-bold">{{ config('app.name', 'Toko Pupuk') }}</h1>
                        <p>Jl. Contoh No. 123, Kota</p>
                        <p>Telp: (123) 456-7890</p>
                    </div>

                    <!-- Invoice Info -->
                    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Sale Information -->
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-2">
                                <svg class="w-5 h-5 inline mr-1 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Informasi Penjualan
                            </h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-500">No. Invoice</dt>
                                    <dd class="text-right">{{ $sale->invoice_number }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-500">Tanggal</dt>
                                    <dd class="text-right">{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-500">Status</dt>
                                    <dd class="text-right">
                                        @if ($sale->trashed())
                                            <span
                                                class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                                Batal
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                                Berhasil
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Customer Information -->
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-2">
                                <svg class="w-5 h-5 inline mr-1 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Informasi Pembeli
                            </h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-500">Nama Pembeli</dt>
                                    <dd class="text-right">{{ $sale->customer->nama ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-500">Metode Pembayaran</dt>
                                    <dd class="text-right capitalize">
                                        {{ $sale->payment_method }}
                                        @if ($sale->payment_method === 'credit')
                                            <span
                                                class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold text-yellow-800 ml-2">Kredit</span>
                                        @endif
                                    </dd>
                                </div>
                                @if ($sale->payment_method === 'credit')
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-500">Uang Muka (DP)</dt>
                                        <dd class="text-right">Rp {{ number_format($sale->down_payment, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                @endif

                                @if ($sale->payment_status !== 'paid')
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-500">Sisa Hutang</dt>
                                        <dd class="text-right font-medium text-red-600">Rp
                                            {{ number_format($sale->remaining_amount, 0, ',', '.') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-500">Jatuh Tempo</dt>
                                        <dd
                                            class="text-right {{ \Carbon\Carbon::parse($sale->due_date)->isPast() ? 'text-red-600' : '' }}">
                                            {{ $sale->due_date ? \Carbon\Carbon::parse($sale->due_date)->format('d/m/Y') : '-' }}
                                            @if ($sale->due_date && \Carbon\Carbon::parse($sale->due_date)->isPast())
                                                <span
                                                    class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold text-red-800 ml-1">Overdue</span>
                                            @endif
                                        </dd>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-500">Kasir</dt>
                                    <dd class="text-right">{{ $sale->user->name }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Payment Details -->
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-2">
                                <svg class="w-5 h-5 inline mr-1 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                Payment Details
                            </h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-500">Total Belanja</dt>
                                    <dd class="text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-500">Potongan</dt>
                                    <dd class="text-right">Rp {{ number_format($sale->discount, 0, ',', '.') }}</dd>
                                </div>
                                <div class="flex justify-between font-medium">
                                    <dt class="text-gray-500">Total Setelah Potongan</dt>
                                    <dd class="text-right">Rp
                                        {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}</dd>
                                </div>
                                <div class="border-t pt-2 mt-2">
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-500">Uang Muka</dt>
                                        <dd class="text-right">Rp
                                            {{ number_format($sale->down_payment, 0, ',', '.') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-500">Sudah Dibayar</dt>
                                        <dd class="text-right">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-500">Sisa Hutang</dt>
                                        <dd
                                            class="text-right font-medium {{ $sale->remaining_amount > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                            Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-500">Kembalian</dt>
                                        <dd class="text-right">Rp
                                            {{ number_format($sale->change_amount, 0, ',', '.') }}</dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Sale Items -->
                    <div class="mt-8 border-t pt-6">
                        <h3 class="mb-4 text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Sale Items
                        </h3>
                        <div class="overflow-x-auto rounded-lg shadow">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Product</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Price</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Quantity</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($sale->saleDetails as $index => $detail)
                                        <tr
                                            class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors duration-150 ease-in-out">
                                            <td class="whitespace-nowrap px-6 py-4">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">{{ $detail->product->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $detail->product->code }}</div>
                                            </td>
                                            <td class="px-6 py-4">Rp
                                                {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4">{{ $detail->quantity }}</td>
                                            <td class="px-6 py-4 font-medium">Rp
                                                {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-100">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right font-medium">Total:</td>
                                        <td class="px-6 py-4 font-bold">Rp
                                            {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    @if ($sale->discount > 0)
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Discount:</td>
                                            <td class="px-6 py-4">Rp {{ number_format($sale->discount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Total After
                                                Discount:</td>
                                            <td class="px-6 py-4 font-bold">Rp
                                                {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right font-medium">Paid Amount:</td>
                                        <td class="px-6 py-4">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @if ($sale->change_amount > 0)
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Change:</td>
                                            <td class="px-6 py-4">Rp
                                                {{ number_format($sale->change_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                    @if ($sale->remaining_amount > 0)
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-medium">Remaining:
                                            </td>
                                            <td class="px-6 py-4 font-bold text-red-600">Rp
                                                {{ number_format($sale->remaining_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    @if ($sale->notes)
                        <div class="mt-6 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Notes
                            </h3>
                            <div class="mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-600">
                                {{ $sale->notes }}
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-6 flex justify-end space-x-3 print:hidden">
                        <x-secondary-button type="button" onclick="window.history.back()" class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back
                        </x-secondary-button>
                        @unless ($sale->trashed())
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-danger-button
                                    onclick="return confirm('Are you sure you want to void this sale? This will return the products to inventory.')"
                                    class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Void Sale
                                </x-danger-button>
                            </form>
                        @endunless
                    </div>

                    <!-- Thank You Message (Visible when printing) -->
                    <div class="hidden print:block mt-8 text-center">
                        <p class="text-sm text-gray-600">Thank you for your purchase!</p>
                        <p class="text-xs text-gray-500 mt-2">Please keep this invoice for your records.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .print-container,
            .print-container * {
                visibility: visible;
            }

            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .print:hidden {
                display: block !important;
            }

            .no-print {
                display: none !important;
            }

            @page {
                margin: 2cm;
                size: A4 portrait;
            }
        }
    </style>
</x-app-layout>
