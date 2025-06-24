{{-- resources/views/categories/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Category Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Category Information</h3>
                        <dl class="mt-4 grid grid-cols-1 gap-4">
                            <div>
                                <dt class="font-medium text-gray-500">Name</dt>
                                <dd class="mt-1">{{ $category->name }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Description</dt>
                                <dd class="mt-1">{{ $category->description ?? 'No description' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Total Products</dt>
                                <dd class="mt-1">{{ $category->products->count() }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900">Products in this Category</h3>
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Code</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Name</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Stock</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Price</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse ($category->products as $product)
                                        <tr>
                                            <td class="whitespace-nowrap px-6 py-4">{{ $product->code }}</td>
                                            <td class="px-6 py-4">{{ $product->name }}</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="@if ($product->stock <= $product->min_stock) text-red-600 font-bold @endif">
                                                    {{ $product->stock }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">Rp
                                                {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                            <td class="whitespace-nowrap px-6 py-4 space-x-2">
                                                <a href="{{ route('products.show', $product) }}"
                                                    class="text-blue-600 hover:text-blue-900">View</a>
                                                <a href="{{ route('products.edit', $product) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                                No products found in this category.
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
                        <a href="{{ route('categories.edit', $category) }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Edit Category
                        </a>
                        @if ($category->products->isEmpty())
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500"
                                    onclick="return confirm('Are you sure you want to delete this category?')">
                                    Delete Category
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
