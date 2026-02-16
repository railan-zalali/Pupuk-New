<table class="data-table">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Faktur</th>
            <th>Pelanggan</th>
            <th>Total</th>
            <th>Pembayaran</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($sales as $sale)
        <tr class="{{ $sale->trashed() ? 'bg-red-50/50 dark:bg-red-500/5' : '' }}">
            <td>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $sale->created_at->format('d/m/Y') }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $sale->created_at->format('H:i') }}</p>
                </div>
            </td>
            <td>
                <a href="{{ route('sales.show', $sale) }}" class="text-sm font-semibold text-gray-800 dark:text-gray-100 hover:text-emerald-600 dark:hover:text-emerald-400">
                    {{ $sale->invoice_number }}
                </a>
            </td>
            <td>
                @if ($sale->customer)
                <span class="text-sm text-gray-700 dark:text-gray-200">{{ $sale->customer->nama }}</span>
                @else
                <span class="text-sm text-gray-400">Umum</span>
                @endif
            </td>
            <td>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-100 tabular-nums">Rp {{ number_format($sale->total_amount - $sale->discount, 0, ',', '.') }}</span>
            </td>
            <td>
                @if ($sale->payment_method == 'cash')
                <span class="badge badge-success"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tunai</span>
                @elseif ($sale->payment_method == 'transfer')
                <span class="badge badge-info"><span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Transfer</span>
                @elseif ($sale->payment_method == 'credit')
                <span class="badge badge-warning"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Kredit</span>
                @if ($sale->remaining_amount > 0)
                <p class="text-xs text-red-500 mt-1">Sisa: Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}</p>
                @endif
                @endif
            </td>
            <td>
                @if ($sale->trashed())
                <span class="badge badge-danger"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Dibatalkan</span>
                @elseif ($sale->status == 'draft')
                <span class="badge badge-neutral"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft</span>
                @elseif ($sale->status == 'processing')
                <span class="badge badge-info"><span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Diproses</span>
                @else
                <span class="badge badge-success"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai</span>
                @endif
            </td>
            <td>
                <div class="flex items-center gap-1">
                    <a href="{{ route('sales.show', $sale) }}" class="action-btn-view" title="Lihat">
                        <i class="ti ti-eye text-base"></i>
                    </a>
                    @if ($sale->status == 'draft')
                    <a href="{{ route('drafts.edit', $sale) }}" class="action-btn-edit" title="Edit">
                        <i class="ti ti-edit text-base"></i>
                    </a>
                    @endif
                    @if (!$sale->trashed())
                    <button type="button" class="action-btn-delete cancel-sale-btn"
                        data-sale-id="{{ $sale->id }}" data-invoice="{{ $sale->invoice_number }}" title="Batalkan">
                        <i class="ti ti-trash text-base"></i>
                    </button>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center py-14">
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-gray-700 flex items-center justify-center mb-3">
                        <i class="ti ti-receipt-off text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tidak ada transaksi ditemukan</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Coba ubah filter pencarian</p>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="mt-4 px-5 pb-3">
    {{ $sales->links() }}
</div>