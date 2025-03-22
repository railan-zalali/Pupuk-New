<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Purchase Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Purchase Information</h3>
                        <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <dt class="font-medium text-gray-500">Invoice Number</dt>
                                <dd class="mt-1">{{ $purchase->invoice_number }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Date</dt>
                                <dd class="mt-1">{{ $purchase->date->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    @if ($purchase->trashed())
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                            Void
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                            Active
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Supplier</dt>
                                <dd class="mt-1">{{ $purchase->supplier->name }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Created By</dt>
                                <dd class="mt-1">{{ $purchase->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Total Amount</dt>
                                <dd class="mt-1 font-semibold">Rp
                                    {{ number_format($purchase->total_amount, 0, ',', '.') }}</dd>
                            </div>
                            @if ($purchase->notes)
                                <div class="sm:col-span-3">
                                    <dt class="font-medium text-gray-500">Notes</dt>
                                    <dd class="mt-1">{{ $purchase->notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900">Purchase Items</h3>
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Product</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Code</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Quantity</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Purchase Price</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($purchase->purchaseDetails as $detail)
                                        <tr>
                                            <td class="px-6 py-4">{{ $detail->product->name }}</td>
                                            <td class="px-6 py-4">{{ $detail->product->code }}</td>
                                            <td class="px-6 py-4">{{ $detail->quantity }}</td>
                                            <td class="px-6 py-4">Rp
                                                {{ number_format($detail->purchase_price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4">Rp
                                                {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right font-medium">Total:</td>
                                        <td class="px-6 py-4 font-bold">Rp
                                            {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <x-secondary-button onclick="window.history.back()">
                            Back
                        </x-secondary-button>
                        @unless ($purchase->trashed())
                            <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-danger-button
                                    onclick="return confirm('Are you sure you want to void this purchase? This will adjust the product stock accordingly.')">
                                    Void Purchase
                                </x-danger-button>
                            </form>
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
