<x-app-layout>
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
                        {{ __('Invoice') }} #{{ $sale->invoice_number }}
                    </span>
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($sale->date)->format('d F Y, H:i') }}
                    </span>
                    <span class="mx-2">•</span>
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ $sale->customer->nama ?? 'Pelanggan Umum' }}
                    </span>
                </p>
            </div>

            <div class="flex space-x-3">
                <button type="button" onclick="window.print()"
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 border border-gray-300 shadow-sm hover:bg-gray-50 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Print Invoice
                </button>
                <a href="{{ route('sales.invoice', $sale) }}" target="_blank"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
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
                    <!-- Status Banner -->
                    <div class="mb-6">
                        @if ($sale->trashed())
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-red-800">
                                            Transaksi ini telah dibatalkan pada
                                            {{ $sale->deleted_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif ($sale->payment_method === 'credit' && $sale->remaining_amount > 0)
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-yellow-800">
                                            Transaksi ini memiliki sisa pembayaran kredit sebesar Rp
                                            {{ number_format($sale->remaining_amount, 0, ',', '.') }}
                                            @if ($sale->due_date)
                                                <span class="ml-2">•</span>
                                                <span class="ml-2">Jatuh tempo:
                                                    {{ \Carbon\Carbon::parse($sale->due_date)->format('d/m/Y') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif ($sale->payment_method === 'credit' && $sale->remaining_amount <= 0)
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">
                                            Pembayaran kredit untuk transaksi ini telah lunas
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Informasi Perusahaan (Tampil saat cetak) -->
                    <div class="hidden print:block mb-8">
                        <div class="text-center mb-6">
                            <h1 class="text-2xl font-bold">{{ config('app.name', 'Toko Pupuk') }}</h1>
                            <p>Jl. Contoh No. 123, Kota</p>
                            <p>Telp: (123) 456-7890</p>
                        </div>
                        <div class="text-center mb-4">
                            <h2 class="text-xl font-semibold">INVOICE</h2>
                            <p>{{ $sale->invoice_number }}</p>
                        </div>
                    </div>

                    <!-- Informasi Transaksi & Pembeli -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Informasi Transaksi -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Informasi Transaksi
                            </h3>

                            <div class="space-y-3">
                                <div class="grid grid-cols-2 border-b border-gray-100 pb-2">
                                    <div class="text-sm text-gray-500">No. Invoice</div>
                                    <div class="text-sm font-medium text-gray-900">{{ $sale->invoice_number }}</div>
                                </div>

                                <div class="grid grid-cols-2 border-b border-gray-100 pb-2">
                                    <div class="text-sm text-gray-500">Tanggal</div>
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y H:i') }}</div>
                                </div>

                                <div class="grid grid-cols-2 border-b border-gray-100 pb-2">
                                    <div class="text-sm text-gray-500">Status</div>
                                    <div>
                                        @if ($sale->trashed())
                                            <span
                                                class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                <svg class="mr-1 h-2 w-2 text-red-400" fill="currentColor"
                                                    viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Dibatalkan
                                            </span>
                                        @elseif ($sale->status === 'draft')
                                            <span
                                                class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                <svg class="mr-1 h-2 w-2 text-yellow-400" fill="currentColor"
                                                    viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Draft
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                <svg class="mr-1 h-2 w-2 text-green-400" fill="currentColor"
                                                    viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Selesai
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 border-b border-gray-100 pb-2">
                                    <div class="text-sm text-gray-500">Metode Pembayaran</div>
                                    <div class="text-sm text-gray-900 capitalize">
                                        @if ($sale->payment_method === 'cash')
                                            <span class="inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-green-500" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Tunai
                                            </span>
                                        @elseif ($sale->payment_method === 'credit')
                                            <span class="inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 mr-1 text-yellow-500" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Kredit
                                            </span>
                                        @else
                                            {{ $sale->payment_method }}
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-2">
                                    <div class="text-sm text-gray-500">Kasir</div>
                                    <div class="text-sm text-gray-900">{{ $sale->user->name }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pembeli -->
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Informasi Pembeli
                            </h3>

                            @if ($sale->customer)
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 border-b border-gray-100 pb-2">
                                        <div class="text-sm text-gray-500">Nama</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $sale->customer->nama }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 border-b border-gray-100 pb-2">
                                        <div class="text-sm text-gray-500">NIK</div>
                                        <div class="text-sm text-gray-900">{{ $sale->customer->nik }}</div>
                                    </div>

                                    <div class="border-b border-gray-100 pb-2">
                                        <div class="text-sm text-gray-500 mb-1">Alamat</div>
                                        <div class="text-sm text-gray-900">
                                            {{ $sale->customer->alamat ?? '-' }},
                                            {{ $sale->customer->desa_nama }},
                                            {{ $sale->customer->kecamatan_nama }}
                                        </div>
                                    </div>

                                    <div>
                                        <a href="{{ route('customers.show', $sale->customer) }}"
                                            class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Detail Pelanggan
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 p-4 rounded-md text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-10 w-10 mx-auto text-gray-400 mb-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <p class="text-gray-500">Pelanggan Umum</p>
                                    <p class="text-gray-400 text-sm mt-1">Tidak ada data pelanggan yang terdaftar</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Detail Pembayaran -->
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Detail Pembayaran
                        </h3>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Total Belanja</dt>
                                <dd class="mt-1 text-xl font-semibold text-gray-900">Rp
                                    {{ number_format($sale->total_amount, 0, ',', '.') }}</dd>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Potongan</dt>
                                <dd class="mt-1 text-xl font-semibold text-gray-900">Rp
                                    {{ number_format($sale->discount, 0, ',', '.') }}</dd>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Total Setelah Potongan</dt>
                                <dd class="mt-1 text-xl font-semibold text-indigo-700">Rp
                                    {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}</dd>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Uang Muka</dt>
                                <dd class="mt-1 text-xl font-semibold text-gray-900">Rp
                                    {{ number_format($sale->down_payment, 0, ',', '.') }}</dd>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Sudah Dibayar</dt>
                                <dd class="mt-1 text-xl font-semibold text-green-600">Rp
                                    {{ number_format($sale->paid_amount, 0, ',', '.') }}</dd>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Sisa Hutang</dt>
                                <dd
                                    class="mt-1 text-xl font-semibold {{ $sale->remaining_amount > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}
                                </dd>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">Kembalian</dt>
                                <dd class="mt-1 text-xl font-semibold text-gray-900">Rp
                                    {{ number_format($sale->change_amount, 0, ',', '.') }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Item Penjualan -->
                    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Item Penjualan
                        </h3>

                        <div class="overflow-x-auto">
                            <div class="border rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                                No</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                                Produk</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                                Harga</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                                Jumlah</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                                Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($sale->saleDetails as $index => $detail)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                                    {{ $index + 1 }}</td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded-md flex items-center justify-center mr-3">
                                                            @if ($detail->product->image_path)
                                                                <img src="{{ Storage::url($detail->product->image_path) }}"
                                                                    alt="{{ $detail->product->name }}"
                                                                    class="h-full w-full object-cover rounded-md">
                                                            @else
                                                                <svg class="h-6 w-6 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="font-medium text-gray-900">
                                                                {{ $detail->product->name }}</div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ $detail->product->code }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-700">Rp
                                                    {{ number_format($detail->selling_price, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-700">{{ $detail->quantity }}
                                                </td>
                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp
                                                    {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-right font-medium text-gray-700">
                                                Total:</td>
                                            <td class="px-6 py-4 font-bold text-gray-900">Rp
                                                {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                        @if ($sale->discount > 0)
                                            <tr>
                                                <td colspan="4"
                                                    class="px-6 py-4 text-right font-medium text-gray-700">
                                                    Diskon:</td>
                                                <td class="px-6 py-4 font-medium text-gray-900">- Rp
                                                    {{ number_format($sale->discount, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"
                                                    class="px-6 py-4 text-right font-medium text-gray-700">
                                                    Total Setelah Diskon:</td>
                                                <td class="px-6 py-4 font-bold text-indigo-700">Rp
                                                    {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if ($sale->notes)
                        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Catatan
                            </h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-gray-700">{{ $sale->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-6 flex justify-end space-x-3 print:hidden">
                        <button type="button" onclick="window.history.back()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </button>

                        @if ($sale->payment_method === 'credit' && $sale->remaining_amount > 0 && !$sale->trashed())
                            <a href="{{ route('sales.credit', ['sale_id' => $sale->id]) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Terima Pembayaran
                            </a>
                        @endif

                        @unless ($sale->trashed())
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan penjualan ini? Ini akan mengembalikan produk ke inventaris.')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Batalkan Penjualan
                                </button>
                            </form>
                        @endunless
                    </div>

                    <!-- Pesan Terima Kasih (Tampil saat cetak) -->
                    <div class="hidden print:block mt-8 text-center">
                        <p class="text-sm text-gray-600">Terima kasih atas pembelian Anda!</p>
                        <p class="text-xs text-gray-500 mt-2">Simpan invoice ini untuk referensi.</p>
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
            }
        }
    </style>
</x-app-layout>
