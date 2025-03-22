<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Profit & Loss Report') }}
            </h2>
            <x-report-export-buttons route="reports.profit-loss" :parameters="request()->all()" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Date Filter --}}
            <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
                <form action="{{ route('reports.profit-loss') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="start_date" value="Start Date" />
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                            :value="request('start_date', $startDate->format('Y-m-d'))" required />
                    </div>

                    <div>
                        <x-input-label for="end_date" value="End Date" />
                        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full"
                            :value="request('end_date', $endDate->format('Y-m-d'))" required />
                    </div>

                    <div class="flex items-end">
                        <x-primary-button class="w-full justify-center">
                            Generate Report
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Sales Card --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Sales</h3>
                    <p class="mt-2 text-3xl font-bold text-green-600">
                        Rp {{ number_format($totalSales, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Purchases Card --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Purchases</h3>
                    <p class="mt-2 text-3xl font-bold text-red-600">
                        Rp {{ number_format($totalPurchases, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Gross Profit Card --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500">Gross Profit</h3>
                    <p class="mt-2 text-3xl font-bold {{ $grossProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($grossProfit, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Transactions Tables --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Sales Table --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sales Transactions</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Invoice</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($sales as $sale)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $sale->created_at->format('d/m/Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:text-blue-900">
                                            <a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                            No sales found in this period.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Purchases Table --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Purchase Transactions</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Invoice</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($purchases as $purchase)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $purchase->created_at->format('d/m/Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:text-blue-900">
                                            <a
                                                href="{{ route('purchases.show', $purchase) }}">{{ $purchase->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                            No purchases found in this period.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
