<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Stock Report') }}
            </h2>
            <x-report-export-buttons route="reports.stock" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Summary Cards --}}
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Products</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($summary['total_products']) }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Stock Value</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">Rp
                        {{ number_format($summary['total_stock_value'], 0, ',', '.') }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-sm font-medium text-gray-500">Low Stock Items</h3>
                    <p class="mt-2 text-3xl font-bold text-red-600">{{ number_format($summary['low_stock_count']) }}</p>
                </div>
            </div>

            {{-- Stock Table --}}
            <div class="bg-white rounded-lg shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Current Stock</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Min Stock</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Value</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->category->name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $product->stock <= $product->min_stock ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $product->stock }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->min_stock }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($product->stock_value, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($product->stock <= $product->min_stock)
                                            <span
                                                class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-red-100 text-red-800">
                                                Low Stock
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 bg-green-100 text-green-800">
                                                In Stock
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
