<x-app-layout>
    <x-slot name="header">Detail Transaksi</x-slot>

    <div class="space-y-5">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 animate-fade-in-up">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-file-invoice text-emerald-600 dark:text-emerald-400 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                        {{ $sale->invoice_number }}
                    </h2>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5 flex items-center gap-1.5">
                        <i class="ti ti-calendar text-xs"></i>
                        {{ \Carbon\Carbon::parse($sale->date)->setTimezone('Asia/Jakarta')->format('d F Y, H:i') }}
                    </p>
                    <div class="flex items-center gap-2 mt-2">
                        @if ($sale->trashed())
                        <span class="badge badge-danger"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Dibatalkan</span>
                        @elseif ($sale->status === 'draft')
                        <span class="badge badge-neutral"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft</span>
                        @elseif ($sale->status === 'processing')
                        <span class="badge badge-info"><span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Diproses</span>
                        @else
                        <span class="badge badge-success"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai</span>
                        @endif
                        @if ($sale->payment_method === 'credit')
                        @if ($sale->remaining_amount > 0)
                        <span class="badge badge-warning"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Kredit (Belum Lunas)</span>
                        @else
                        <span class="badge badge-success"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Kredit (Lunas)</span>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" onclick="window.print()" class="btn-ghost btn-sm">
                    <i class="ti ti-printer text-base"></i> Print
                </button>

                @php
                $hasSeedProducts = $sale->saleDetails->filter(fn($d) => $d->product->category && strtolower($d->product->category->name) === 'benih')->isNotEmpty();
                $hasNonSeedProducts = $sale->saleDetails->filter(fn($d) => !$d->product->category || strtolower($d->product->category->name) !== 'benih')->isNotEmpty();
                @endphp

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="btn-primary btn-sm">
                        <i class="ti ti-file-text text-base"></i> Cetak Dokumen
                        <i class="ti ti-chevron-down text-xs ml-0.5"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 z-20 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-1.5 overflow-hidden">
                        @if ($hasNonSeedProducts)
                        <a href="{{ route('sales.invoice', $sale) }}" target="_blank"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <i class="ti ti-file-invoice text-gray-400"></i>
                            <div>
                                <p class="font-medium">Invoice Biasa</p>
                                <p class="text-xs text-gray-400 mt-0.5">Untuk produk umum</p>
                            </div>
                        </a>
                        @endif
                        @if ($hasSeedProducts)
                        <a href="{{ route('sales.invoice-seeds', $sale) }}" target="_blank"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <i class="ti ti-leaf text-emerald-500"></i>
                            <div>
                                <p class="font-medium">Invoice Benih</p>
                                <p class="text-xs text-gray-400 mt-0.5">Dengan cap PPN dibebaskan</p>
                            </div>
                        </a>
                        @endif
                        <a href="{{ route('sales.delivery-note', $sale) }}" target="_blank"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <i class="ti ti-truck text-sky-500"></i>
                            <div>
                                <p class="font-medium">Surat Jalan</p>
                                <p class="text-xs text-gray-400 mt-0.5">Dokumen pengiriman barang</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Timeline --}}
        <div class="card p-6 animate-fade-in-up stagger-2">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <i class="ti ti-timeline text-emerald-500"></i> Status Transaksi
            </h3>
            <div class="relative pl-6">
                <div class="absolute left-[9px] top-2 bottom-2 w-0.5 bg-gray-100 dark:bg-gray-700"></div>
                <div class="space-y-5">
                    {{-- Created --}}
                    <div class="relative flex items-start gap-3">
                        <div class="absolute -left-6 w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center ring-4 ring-white dark:ring-gray-800 z-10">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Transaksi Dibuat</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($sale->created_at)->setTimezone('Asia/Jakarta')->format('d F Y, H:i') }} — oleh {{ $sale->user->name }}</p>
                        </div>
                    </div>

                    @if ($sale->draft_id)
                    <div class="relative flex items-start gap-3">
                        <div class="absolute -left-6 w-5 h-5 rounded-full bg-sky-100 dark:bg-sky-500/20 flex items-center justify-center ring-4 ring-white dark:ring-gray-800 z-10">
                            <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Dibuat dari Draft</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Draft <a href="{{ route('drafts.show', $sale->draft_id) }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">#{{ $sale->draft->invoice_number }}</a>
                            </p>
                        </div>
                    </div>
                    @endif

                    @if ($sale->payment_method === 'credit')
                    <div class="relative flex items-start gap-3">
                        <div class="absolute -left-6 w-5 h-5 rounded-full {{ $sale->remaining_amount > 0 ? 'bg-amber-100 dark:bg-amber-500/20' : 'bg-emerald-100 dark:bg-emerald-500/20' }} flex items-center justify-center ring-4 ring-white dark:ring-gray-800 z-10">
                            <span class="w-2 h-2 rounded-full {{ $sale->remaining_amount > 0 ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Pembayaran Kredit</p>
                            @if ($sale->remaining_amount > 0)
                            <p class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">
                                Sisa: Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}
                                @if ($sale->due_date) • Jatuh tempo: {{ \Carbon\Carbon::parse($sale->due_date)->format('d/m/Y') }} @endif
                            </p>
                            @else
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-0.5">Kredit telah lunas</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if ($sale->trashed())
                    <div class="relative flex items-start gap-3">
                        <div class="absolute -left-6 w-5 h-5 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center ring-4 ring-white dark:ring-gray-800 z-10">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Transaksi Dibatalkan</p>
                            <p class="text-xs text-red-500 mt-0.5">{{ $sale->deleted_at->format('d F Y, H:i') }} — Stok telah dikembalikan</p>
                        </div>
                    </div>
                    @else
                    <div class="relative flex items-start gap-3">
                        <div class="absolute -left-6 w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center ring-4 ring-white dark:ring-gray-800 z-10">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-100">Transaksi Selesai</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Metode:
                                @if ($sale->payment_method === 'cash') Tunai
                                @elseif ($sale->payment_method === 'credit') Kredit
                                @elseif ($sale->payment_method === 'transfer') Transfer Bank
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Info Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 animate-fade-in-up stagger-3">
            {{-- Customer Information --}}
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="ti ti-user text-emerald-500"></i> Informasi Pelanggan
                </h3>

                @if ($sale->draft_id)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 mb-4">
                    <i class="ti ti-info-circle text-sky-500"></i>
                    <p class="text-xs text-sky-700 dark:text-sky-300">
                        Dibuat dari draft <a href="{{ route('drafts.show', $sale->draft_id) }}" class="font-semibold hover:underline">#{{ $sale->draft->invoice_number }}</a>
                    </p>
                </div>
                @endif

                @if ($sale->customer)
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Nama</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $sale->customer->nama }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">NIK</span>
                        <span class="text-sm text-gray-700 dark:text-gray-200 tabular-nums">{{ $sale->customer->nik }}</span>
                    </div>
                    <div class="py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Alamat</span>
                        <p class="text-sm text-gray-700 dark:text-gray-200 mt-1">
                            {{ $sale->customer->alamat ?? '-' }}, {{ $sale->customer->desa_nama }}, {{ $sale->customer->kecamatan_nama }}
                        </p>
                    </div>
                    <a href="{{ route('customers.show', $sale->customer) }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 mt-1">
                        <i class="ti ti-external-link text-sm"></i> Lihat Detail Pelanggan
                    </a>
                </div>
                @else
                <div class="flex flex-col items-center py-6 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-700 flex items-center justify-center mb-3">
                        <i class="ti ti-user-off text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pelanggan Umum</p>
                    <p class="text-xs text-gray-400 mt-0.5">Tidak ada data pelanggan terdaftar</p>
                </div>
                @endif
            </div>

            {{-- Transaction Information --}}
            <div class="card p-5">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="ti ti-clipboard-list text-emerald-500"></i> Informasi Transaksi
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">No. Invoice</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $sale->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Tanggal</span>
                        <span class="text-sm text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($sale->date)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Status</span>
                        <div>
                            @if ($sale->trashed())
                            <span class="badge badge-danger"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Dibatalkan</span>
                            @else
                            <span class="badge badge-success"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Pembayaran</span>
                        <div>
                            @if ($sale->payment_method === 'cash')
                            <span class="badge badge-success"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tunai</span>
                            @elseif ($sale->payment_method === 'credit')
                            <span class="badge badge-warning"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Kredit</span>
                            @elseif ($sale->payment_method === 'transfer')
                            <span class="badge badge-info"><span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Transfer</span>
                            @endif
                        </div>
                    </div>
                    @if ($sale->vehicle_type || $sale->vehicle_number)
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 dark:border-gray-700/30">
                        <span class="text-xs text-gray-400">Kendaraan</span>
                        <span class="text-sm text-gray-700 dark:text-gray-200">
                            {{ $sale->vehicle_type ?? '-' }} @if($sale->vehicle_number)({{ $sale->vehicle_number }})@endif
                        </span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center py-2">
                        <span class="text-xs text-gray-400">Kasir</span>
                        <span class="text-sm text-gray-700 dark:text-gray-200">{{ $sale->user->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Summary --}}
        <div class="card overflow-hidden animate-fade-in-up stagger-4">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700/40">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="ti ti-cash text-emerald-500"></i> Detail Pembayaran
                </h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30">
                        <p class="text-xs text-gray-400 mb-1">Total Belanja</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-100 tabular-nums">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/30">
                        <p class="text-xs text-gray-400 mb-1">Potongan</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-100 tabular-nums">Rp {{ number_format($sale->discount, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10">
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mb-1">Total Bayar</p>
                        <p class="text-lg font-bold text-emerald-700 dark:text-emerald-300 tabular-nums">Rp {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-xl {{ $sale->payment_method === 'credit' && $sale->remaining_amount > 0 ? 'bg-amber-50 dark:bg-amber-500/10' : 'bg-emerald-50 dark:bg-emerald-500/10' }}">
                        <p class="text-xs {{ $sale->payment_method === 'credit' && $sale->remaining_amount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }} mb-1">Status</p>
                        <p class="text-lg font-bold {{ $sale->payment_method === 'credit' && $sale->remaining_amount > 0 ? 'text-amber-700 dark:text-amber-300' : 'text-emerald-700 dark:text-emerald-300' }}">
                            @if ($sale->payment_method === 'credit')
                            @if ($sale->remaining_amount > 0) Belum Lunas @else Lunas @endif
                            @else
                            Lunas
                            @endif
                        </p>
                    </div>
                </div>

                @if ($sale->payment_method === 'credit')
                <div class="mt-4 p-4 rounded-xl bg-amber-50/50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/20">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Uang Muka</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-100 tabular-nums">Rp {{ number_format($sale->down_payment, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Total Terbayar</p>
                            <p class="font-semibold text-emerald-600 dark:text-emerald-400 tabular-nums">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Sisa Hutang</p>
                            <p class="font-semibold {{ $sale->remaining_amount > 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }} tabular-nums">Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}</p>
                        </div>
                        @if ($sale->due_date)
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Jatuh Tempo</p>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">{{ \Carbon\Carbon::parse($sale->due_date)->format('d F Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Sale Items Table --}}
        <div class="card overflow-hidden animate-fade-in-up stagger-5">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-700/40">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="ti ti-shopping-cart text-emerald-500"></i> Item Penjualan
                </h3>
                <div class="flex items-center gap-2">
                    <span class="badge badge-primary">{{ $sale->saleDetails->count() }} item</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->saleDetails as $detail)
                        <tr>
                            <td>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $detail->product->name }}</p>
                                    @if($detail->product->code)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $detail->product->code }}</p>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ strtolower($detail->product->category->name ?? '') === 'benih' ? 'badge-success' : 'badge-primary' }}">
                                    {{ $detail->product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-200">{{ $detail->productUnit->unit->name }}</p>
                                    <p class="text-xs text-gray-400">({{ $detail->productUnit->unit->abbreviation }})</p>
                                </div>
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-sm font-semibold text-gray-800 dark:text-gray-100 tabular-nums">
                                    {{ $detail->quantity }}
                                </span>
                            </td>
                            <td>
                                <p class="text-sm text-gray-700 dark:text-gray-200 tabular-nums">Rp {{ number_format($detail->price, 0, ',', '.') }}</p>
                                @if($detail->productUnit->conversion > 1)
                                <p class="text-xs text-gray-400 mt-0.5">Per {{ $detail->productUnit->unit->abbreviation }}</p>
                                @endif
                            </td>
                            <td class="text-right">
                                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100 tabular-nums">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-emerald-50/50 dark:bg-emerald-500/5">
                            <td colspan="5" class="text-right text-sm font-semibold text-gray-800 dark:text-gray-100">Total</td>
                            <td class="text-right">
                                <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400 tabular-nums">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Notes --}}
        @if ($sale->notes)
        <div class="card p-5 animate-fade-in-up stagger-6">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3 flex items-center gap-2">
                <i class="ti ti-notes text-emerald-500"></i> Catatan
            </h3>
            <div class="p-4 rounded-xl bg-amber-50/50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/20">
                <p class="text-sm text-gray-700 dark:text-gray-300 italic leading-relaxed">{{ $sale->notes }}</p>
            </div>
        </div>
        @endif

        {{-- Action Buttons Footer --}}
        <div class="card p-5 print:hidden animate-fade-in-up stagger-6">
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="window.history.back()" class="btn-ghost btn-sm">
                    <i class="ti ti-arrow-left text-base"></i> Kembali
                </button>
                <button type="button" onclick="window.print()" class="btn-secondary btn-sm">
                    <i class="ti ti-printer text-base"></i> Cetak
                </button>
                @if ($sale->payment_method === 'credit' && $sale->remaining_amount > 0 && !$sale->trashed())
                <a href="{{ route('sales.credit', ['sale_id' => $sale->id]) }}" class="btn-primary btn-sm">
                    <i class="ti ti-cash text-base"></i> Terima Pembayaran
                </a>
                @endif
                @unless ($sale->trashed())
                <button type="button" class="cancel-sale-btn btn-sm bg-red-600 hover:bg-red-700 text-white rounded-xl px-4 py-2 text-xs font-semibold transition-colors"
                    data-sale-id="{{ $sale->id }}" data-invoice="{{ $sale->invoice_number }}">
                    <i class="ti ti-trash text-base"></i> Batalkan
                </button>
                @endunless
            </div>
        </div>
    </div>

    {{-- Cancel Sale Modal --}}
    <div id="cancelSaleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" aria-hidden="true"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center flex-shrink-0">
                            <i class="ti ti-alert-triangle text-red-500 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Konfirmasi Pembatalan</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                Apakah Anda yakin ingin membatalkan penjualan <span id="invoice-number" class="font-semibold text-gray-800 dark:text-gray-200"></span>?
                            </p>
                            <p class="text-xs text-gray-400 mt-1">Tindakan ini akan mengembalikan semua produk ke inventaris (FIFO).</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700/40">
                    <button type="button" id="cancelModalBtn" class="btn-ghost btn-sm">Batal</button>
                    <form id="cancelSaleForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-sm bg-red-600 hover:bg-red-700 text-white rounded-xl px-4 py-2 text-xs font-semibold transition-colors">
                            Batalkan Penjualan
                        </button>
                    </form>
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

            .no-print,
            .print\:hidden {
                display: none !important;
            }

            @page {
                margin: 2cm;
                size: auto;
            }
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('cancelSaleModal');
            const invoiceSpan = document.getElementById('invoice-number');
            const form = document.getElementById('cancelSaleForm');

            document.querySelectorAll('.cancel-sale-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    invoiceSpan.textContent = this.dataset.invoice;
                    form.action = `/sales/${this.dataset.saleId}`;
                    modal.classList.remove('hidden');
                });
            });

            document.getElementById('cancelModalBtn').addEventListener('click', () => modal.classList.add('hidden'));
            modal.addEventListener('click', e => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });
    </script>
    @endpush
</x-app-layout>