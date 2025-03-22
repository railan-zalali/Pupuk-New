<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Supplier Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Supplier Information</h3>
                            <a href="{{ route('purchases.create') }}?supplier_id={{ $supplier->id }}"
                                class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                                Create New Purchase
                            </a>
                        </div>
                        <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="font-medium text-gray-500">Name</dt>
                                <dd class="mt-1">{{ $supplier->name }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1">{{ $supplier->phone }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Address</dt>
                                <dd class="mt-1">{{ $supplier->address }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Description</dt>
                                <dd class="mt-1">{{ $supplier->description ?? 'No description' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Total Purchases</dt>
                                <dd class="mt-1">{{ $supplier->purchases->count() }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Total Amount</dt>
                                <dd class="mt-1">Rp
                                    {{ number_format($supplier->purchases->sum('total_amount'), 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900">Purchase History</h3>
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Date</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Invoice</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Total Items</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Total Amount</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($supplier->purchases as $purchase)
                                        <tr>
                                            <td class="whitespace-nowrap px-6 py-4">
                                                {{ $purchase->date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4">{{ $purchase->invoice_number }}</td>
                                            <td class="px-6 py-4">{{ $purchase->purchaseDetails->sum('quantity') }}
                                            </td>
                                            <td class="px-6 py-4">Rp
                                                {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4">
                                                @if ($purchase->trashed())
                                                    <span
                                                        class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                                        Void
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                                        Completed
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 space-x-2">
                                                <a href="{{ route('purchases.show', $purchase) }}"
                                                    class="text-blue-600 hover:text-blue-900">View</a>
                                                @unless ($purchase->trashed())
                                                    <form action="{{ route('purchases.destroy', $purchase) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Are you sure you want to void this purchase?')">
                                                            Void
                                                        </button>
                                                    </form>
                                                @endunless
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                                No purchase history found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <x-secondary-button onclick="window.history.back()">
                            Back
                        </x-secondary-button>
                        <a href="{{ route('suppliers.edit', $supplier) }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Edit Supplier
                        </a>
                        @if ($supplier->purchases->isEmpty())
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500"
                                    onclick="return confirm('Are you sure you want to delete this supplier?')">
                                    Delete Supplier
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
