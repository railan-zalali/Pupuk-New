<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Sales Report') }}
            </h2>
            <x-report-export-buttons route="reports.sales" :parameters="[
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
                'payment_method' => request('payment_method'),
            ]" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Filters --}}
            <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
                <form action="{{ route('reports.sales') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <x-input-label for="start_date" value="Start Date" />
                        <x-text-input type="date" name="start_date" id="start_date"
                            value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                            class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="end_date" value="End Date" />
                        <x-text-input type="date" name="end_date" id="end_date"
                            value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="payment_method" value="Payment Method" />
                        <select name="payment_method" id="payment_method"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Methods</option>
                            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>
                                Transfer</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <x-primary-button type="submit" class="w-full justify-center">
                            Apply Filters
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Sales</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $summary['total_sales'] }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Amount</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">Rp
                        {{ number_format($summary['total_amount'], 0, ',', '.') }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500">Average Sale</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">Rp
                        {{ number_format($summary['average_sale'], 0, ',', '.') }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500">Payment Methods</h3>
                    <div class="mt-2 space-y-1">
                        @foreach ($summary['payment_methods'] as $method => $count)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">{{ ucfirst($method) }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sales Table --}}
            <div class="bg-white rounded-lg shadow-sm">
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
                                    Customer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Payment</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $sale->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $sale->invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $sale->customer ? $sale->customer->name : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ ucfirst($sale->payment_method) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $sale->trashed() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $sale->trashed() ? 'Void' : 'Completed' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('sales.show', $sale) }}"
                                            class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No sales found in this period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
