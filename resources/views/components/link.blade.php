<a
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-2 bg-transparent font-medium text-sm text-blue-600 hover:text-blue-800 focus:outline-none focus:underline transition-colors duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</a>
