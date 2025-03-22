<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold text-red-600 mb-4">403</h1>
                        <h2 class="text-2xl font-semibold mb-4">Unauthorized Access</h2>
                        <p class="text-gray-600 mb-8">Sorry, you don't have permission to access this page.</p>
                        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">
                            Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
