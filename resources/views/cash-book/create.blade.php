<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Tambah Transaksi Kas</h2>
                    </div>

                    <form action="{{ route('cash-book.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tanggal -->
                            <div>
                                <x-input-label for="date" value="Tanggal" />
                                <x-text-input id="date" type="date" name="date" class="mt-1 block w-full"
                                    required value="{{ old('date', date('Y-m-d')) }}" />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <!-- No Referensi -->
                            <div>
                                <x-input-label for="reference_number" value="No Referensi" />
                                <x-text-input id="reference_number" type="text" name="reference_number"
                                    class="mt-1 block w-full" value="{{ old('reference_number') }}" />
                                <x-input-error :messages="$errors->get('reference_number')" class="mt-2" />
                            </div>

                            <!-- Keterangan -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" value="Keterangan" />
                                <x-text-input id="description" type="text" name="description"
                                    class="mt-1 block w-full" required value="{{ old('description') }}" />
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Jenis Transaksi -->
                            <div>
                                <x-input-label for="type" value="Jenis Transaksi" />
                                <select id="type" name="type"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                    <option value="debit" {{ old('type') == 'debit' ? 'selected' : '' }}>Debit (Masuk)
                                    </option>
                                    <option value="credit" {{ old('type') == 'credit' ? 'selected' : '' }}>Kredit
                                        (Keluar)</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <!-- Jumlah -->
                            <div>
                                <x-input-label for="amount" value="Jumlah" />
                                <x-text-input id="amount" type="number" name="amount" class="mt-1 block w-full"
                                    required step="0.01" min="0" value="{{ old('amount') }}" />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('cash-book.index') }}"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Batal
                            </a>
                            <x-primary-button>
                                Simpan
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const amountInput = document.getElementById('amount');

                if (amountInput) {
                    amountInput.addEventListener('input', function(e) {
                        // Hapus semua karakter selain angka
                        let value = this.value.replace(/\D/g, '');

                        // Format dengan pemisah ribuan
                        value = new Intl.NumberFormat('id-ID').format(value);

                        // Update nilai input
                        this.value = value;
                    });

                    // Sebelum form di-submit, hapus pemisah ribuan
                    amountInput.closest('form').addEventListener('submit', function() {
                        amountInput.value = amountInput.value.replace(/\D/g, '');
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
