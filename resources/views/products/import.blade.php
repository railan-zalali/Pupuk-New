<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-in-up">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="ti ti-file-import text-xl"></i>
                    </div>
                    {{ __('Import Produk') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 ml-13">
                    <a href="{{ route('products.index') }}" class="hover:text-emerald-500 transition-colors">Produk</a>
                    <span class="mx-1">•</span>
                    <span>Import Data</span>
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    <i class="ti ti-arrow-left text-base"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        @if ($errors->any())
        <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 animate-fade-in-up">
            <i class="ti ti-alert-circle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
            <div>
                <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Terjadi kesalahan import:</h3>
                <ul class="mt-1 text-sm text-red-600 dark:text-red-300 list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up delay-100">
            <!-- Upload Form -->
            <div class="lg:col-span-1">
                <div class="card p-6 h-full">
                    <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                        <i class="ti ti-upload text-emerald-500 text-lg"></i>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Upload File</h3>
                    </div>

                    <form action="{{ route('products.import-process') }}" method="post" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="w-full">
                            <label for="file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 relative overflow-hidden group transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center mb-4 text-emerald-500 group-hover:scale-110 transition-transform">
                                        <i class="ti ti-file-spreadsheet text-3xl"></i>
                                    </div>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 font-medium">Klik untuk upload Excel/CSV</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">XLSX, XLS, CSV (Max. 10MB)</p>
                                </div>
                                <input id="file" name="file" type="file" class="hidden" accept=".xlsx, .xls, .csv" required />
                            </label>
                            <div id="file-name" class="mt-2 text-sm text-center text-gray-600 dark:text-gray-400 hidden"></div>
                        </div>

                        <button type="submit" class="btn-primary w-full justify-center py-3">
                            <i class="ti ti-upload text-lg"></i>
                            <span>Mulai Import</span>
                        </button>
                    </form>

                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700/50">
                        <a href="{{ route('products.template.download') }}" class="btn-secondary w-full justify-center">
                            <i class="ti ti-download text-lg"></i>
                            <span>Download Template</span>
                        </a>
                        <p class="text-xs text-center text-gray-400 mt-2">Gunakan template ini untuk menghindari error.</p>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="lg:col-span-2">
                <div class="card p-6">
                    <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700/50">
                        <i class="ti ti-info-circle text-emerald-500 text-lg"></i>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Petunjuk Format Data</h3>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Pastikan file Excel/CSV Anda memiliki header kolom sesuai tabel berikut. Kolom wajib harus diisi.
                    </p>

                    <div class="overflow-x-auto rounded-lg border border-gray-100 dark:border-gray-700">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Kolom Header</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Keterangan</th>
                                    <th class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 text-center">Wajib?</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr>
                                    <td class="px-4 py-3 font-mono text-emerald-600 dark:text-emerald-400">nama</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Nama lengkap produk</td>
                                    <td class="px-4 py-3 text-center"><span class="badge badge-success">Ya</span></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-mono text-emerald-600 dark:text-emerald-400">kode</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Kode unik produk (auto-generate jika kosong)</td>
                                    <td class="px-4 py-3 text-center"><span class="badge badge-secondary">Tidak</span></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-mono text-emerald-600 dark:text-emerald-400">category_id</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">ID Kategori (lihat tabel referensi)</td>
                                    <td class="px-4 py-3 text-center"><span class="badge badge-secondary">Tidak</span></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-mono text-emerald-600 dark:text-emerald-400">purchase_price</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Harga beli per satuan dasar</td>
                                    <td class="px-4 py-3 text-center"><span class="badge badge-secondary">Tidak</span></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-mono text-emerald-600 dark:text-emerald-400">stock</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">Stok awal</td>
                                    <td class="px-4 py-3 text-center"><span class="badge badge-secondary">Tidak</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3">Referensi Kategori</h4>
                            <div class="max-h-60 overflow-y-auto rounded-lg border border-gray-100 dark:border-gray-700">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">ID</th>
                                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Nama</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach ($categories as $category)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <td class="px-4 py-2 font-mono text-gray-500">{{ $category->id }}</td>
                                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $category->name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-3">Referensi Satuan</h4>
                            <div class="max-h-60 overflow-y-auto rounded-lg border border-gray-100 dark:border-gray-700">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">ID</th>
                                            <th class="px-4 py-2 text-left font-semibold text-gray-600 dark:text-gray-300">Nama</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach ($units as $unit)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <td class="px-4 py-2 font-mono text-gray-500">{{ $unit->id }}</td>
                                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $unit->name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const fileNameDisplay = document.getElementById('file-name');
            if (fileName) {
                fileNameDisplay.textContent = 'File terpilih: ' + fileName;
                fileNameDisplay.classList.remove('hidden');
            } else {
                fileNameDisplay.classList.add('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>