<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Buku Kas</h2>
                        <a href="{{ route('cash-book.create') }}"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Tambah Transaksi
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Keterangan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">No Ref</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Debit</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Kredit</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Saldo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $transaction->date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $transaction->description }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $transaction->reference_number }}</td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            {{ number_format($transaction->debit, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            {{ number_format($transaction->credit, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-medium">
                                            {{ number_format($transaction->balance, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
