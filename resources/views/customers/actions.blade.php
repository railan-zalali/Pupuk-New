<a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900">View</a>
<a href="{{ route('customers.edit', $customer) }}" class="ml-2 text-indigo-600 hover:text-indigo-900">Edit</a>
@if ($customer->sales_count === 0)
    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="ml-2 text-red-600 hover:text-red-900"
            onclick="return confirm('Are you sure you want to delete this customer?')">
            Delete
        </button>
    </form>
@endif
