<x-app-layout>
    <x-slot name="header">Transaksi Penjualan</x-slot>

    <div class="space-y-5">

        {{-- Page Header with Search & Actions --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Transaksi Penjualan</h2>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">Kelola semua transaksi penjualan</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('sales.create') }}" class="btn-primary btn-sm">
                    <i class="ti ti-plus text-sm"></i> Transaksi Baru
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 animate-fade-in-up">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                <i class="ti ti-check text-emerald-600 dark:text-emerald-400"></i>
            </div>
            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
        </div>
        @endif

        {{-- Filters Bar --}}
        <div class="card p-4 animate-fade-in-up stagger-2">
            <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                {{-- Search --}}
                <div class="relative flex-1 max-w-sm">
                    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                    <input type="text" id="searchInput" placeholder="Cari faktur, pelanggan..."
                        class="w-full pl-9 pr-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 dark:focus:border-emerald-400 dark:text-gray-200 placeholder:text-gray-400 transition-shadow"
                        autofocus>
                </div>

                {{-- Filter Dropdowns --}}
                <div class="flex flex-wrap items-center gap-2">
                    <select id="statusFilter" class="text-sm py-2.5 px-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 dark:text-gray-200 cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="completed">Selesai</option>
                        <option value="processing">Diproses</option>
                        <option value="draft">Draft</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                    <select id="paymentFilter" class="text-sm py-2.5 px-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 dark:text-gray-200 cursor-pointer">
                        <option value="">Semua Pembayaran</option>
                        <option value="cash">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="credit">Kredit</option>
                    </select>
                    <button id="dateFilterBtn" class="btn-ghost btn-sm inline-flex items-center gap-1.5">
                        <i class="ti ti-calendar text-base"></i> Tanggal
                    </button>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="card overflow-hidden animate-fade-in-up stagger-3">
            <div id="sales-table-container" class="overflow-x-auto">
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
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($sale->date)->diffForHumans() }}</p>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('sales.show', $sale) }}" class="text-sm font-semibold text-gray-800 dark:text-gray-100 hover:text-emerald-600 dark:hover:text-emerald-400">
                                    {{ $sale->invoice_number }}
                                </a>
                            </td>
                            <td>
                                @if($sale->customer)
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-200">{{ $sale->customer->nama }}</p>
                                    @if($sale->customer->phone)
                                    <p class="text-xs text-gray-400">{{ $sale->customer->phone }}</p>
                                    @endif
                                </div>
                                @else
                                <span class="text-sm text-gray-400">Umum</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 tabular-nums">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</p>
                                    @if($sale->discount > 0)
                                    <p class="text-xs text-gray-400 mt-0.5">Disc: Rp {{ number_format($sale->discount, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($sale->payment_method === 'cash')
                                <span class="badge badge-success"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tunai</span>
                                @elseif($sale->payment_method === 'transfer')
                                <span class="badge badge-info"><span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Transfer</span>
                                @elseif($sale->payment_method === 'credit')
                                <span class="badge badge-warning"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Kredit</span>
                                @if($sale->remaining_amount > 0)
                                <p class="text-xs text-red-500 mt-1">Sisa: Rp {{ number_format($sale->remaining_amount, 0, ',', '.') }}</p>
                                @endif
                                @else
                                <span class="badge badge-neutral">{{ ucfirst($sale->payment_method) }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($sale->trashed())
                                <span class="badge badge-danger"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Dibatalkan</span>
                                @elseif ($sale->status === 'draft')
                                <span class="badge badge-neutral"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft</span>
                                @elseif ($sale->status === 'processing')
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
                                    @unless ($sale->trashed())
                                    @if ($sale->status === 'draft')
                                    <a href="{{ route('sales.edit', $sale) }}" class="action-btn-edit" title="Edit">
                                        <i class="ti ti-edit text-base"></i>
                                    </a>
                                    @endif
                                    <button type="button" class="action-btn-delete cancel-sale-btn"
                                        data-sale-id="{{ $sale->id }}"
                                        data-invoice="{{ $sale->invoice_number }}" title="Batalkan">
                                        <i class="ti ti-trash text-base"></i>
                                    </button>
                                    @endunless
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
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tambahkan transaksi baru untuk memulai</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sales->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700/40">
                {{ $sales->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Cancel Confirmation Modal --}}
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
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Apakah Anda yakin ingin membatalkan transaksi <span id="invoiceNumber" class="font-semibold text-gray-800 dark:text-gray-200"></span>?</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tindakan ini akan mengembalikan stok produk dan tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700/40">
                    <button type="button" id="cancelModalClose" class="btn-ghost btn-sm">Batal</button>
                    <form id="cancelSaleForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-sm bg-red-600 hover:bg-red-700 text-white rounded-xl px-4 py-2 text-sm font-medium transition-colors">
                            Batalkan Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const paymentFilter = document.getElementById('paymentFilter');
            const dateFilterBtn = document.getElementById('dateFilterBtn');
            const dateFilterModal = document.getElementById('dateFilterModal');
            const dateModalClose = document.getElementById('dateModalClose');
            const applyDateFilter = document.getElementById('applyDateFilter');
            const clearDateFilter = document.getElementById('clearDateFilter');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            let debounceTimer;
            let currentFilters = {
                search: '',
                status: '',
                payment_method: '',
                start_date: '',
                end_date: ''
            };

            function loadSalesData() {
                const tableContainer = document.getElementById('sales-table-container');
                tableContainer.innerHTML = '<div class="flex justify-center items-center p-12"><div class="w-8 h-8 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin"></div></div>';

                const params = new URLSearchParams();
                Object.entries(currentFilters).forEach(([k, v]) => {
                    if (v) params.append(k, v);
                });
                params.append('ajax', 1);

                fetch(`${window.location.pathname}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        initCancelButtons();
                    })
                    .catch(() => {
                        tableContainer.innerHTML = '<div class="p-6 text-center text-red-500 text-sm">Gagal memuat data. Silakan coba lagi.</div>';
                    });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    currentFilters.search = this.value;
                    loadSalesData();
                }, 300);
            });
            statusFilter.addEventListener('change', function() {
                currentFilters.status = this.value;
                loadSalesData();
            });
            paymentFilter.addEventListener('change', function() {
                currentFilters.payment_method = this.value;
                loadSalesData();
            });

            dateFilterBtn.addEventListener('click', () => dateFilterModal.classList.remove('hidden'));
            dateModalClose.addEventListener('click', () => dateFilterModal.classList.add('hidden'));
            applyDateFilter.addEventListener('click', function() {
                currentFilters.start_date = startDateInput.value;
                currentFilters.end_date = endDateInput.value;
                loadSalesData();
                dateFilterModal.classList.add('hidden');
            });
            clearDateFilter.addEventListener('click', function() {
                startDateInput.value = '';
                endDateInput.value = '';
                currentFilters.start_date = '';
                currentFilters.end_date = '';
                loadSalesData();
                dateFilterModal.classList.add('hidden');
            });
            window.addEventListener('click', e => {
                if (e.target === dateFilterModal) dateFilterModal.classList.add('hidden');
            });

            // Cancel modal
            const cancelModal = document.getElementById('cancelSaleModal');
            const cancelModalClose = document.getElementById('cancelModalClose');
            const invoiceSpan = document.getElementById('invoiceNumber');
            const cancelForm = document.getElementById('cancelSaleForm');

            function initCancelButtons() {
                document.querySelectorAll('.cancel-sale-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        invoiceSpan.textContent = this.dataset.invoice;
                        cancelForm.action = `/sales/${this.dataset.saleId}`;
                        cancelModal.classList.remove('hidden');
                    });
                });
            }
            initCancelButtons();

            cancelModalClose.addEventListener('click', () => cancelModal.classList.add('hidden'));
            window.addEventListener('click', e => {
                if (e.target === cancelModal) cancelModal.classList.add('hidden');
            });
        });
    </script>
    @endpush

    @include('sales.partials.date_filter_modal')
</x-app-layout>