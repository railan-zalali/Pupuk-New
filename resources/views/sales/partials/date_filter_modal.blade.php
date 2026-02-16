{{-- Date Filter Modal --}}
<div id="dateFilterModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" aria-hidden="true"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center flex-shrink-0">
                        <i class="ti ti-calendar text-sky-500 text-lg"></i>
                    </div>
                    <div class="flex-1 space-y-4">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Filter Tanggal</h3>
                        <div>
                            <label for="start_date" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date"
                                class="w-full text-sm py-2.5 px-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 dark:text-gray-200">
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Tanggal Akhir</label>
                            <input type="date" id="end_date" name="end_date"
                                class="w-full text-sm py-2.5 px-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 dark:text-gray-200">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700/40">
                <button type="button" id="dateModalClose" class="btn-ghost btn-sm">Tutup</button>
                <button type="button" id="clearDateFilter" class="btn-ghost btn-sm">Hapus Filter</button>
                <button type="button" id="applyDateFilter" class="btn-primary btn-sm">Terapkan</button>
            </div>
        </div>
    </div>
</div>