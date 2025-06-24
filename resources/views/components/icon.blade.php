<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150 ease-in-out']) }}>
    {{ $slot }}
</button>
