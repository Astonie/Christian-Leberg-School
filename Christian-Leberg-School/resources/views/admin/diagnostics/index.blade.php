<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Diagnostics & Logs</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-4">Last 200 lines from the application log.</p>

                <div class="mb-4">
                    <form method="POST" action="{{ route('admin.diagnostics.download') }}">
                        @csrf
                        <button class="bg-blue-600 text-white px-3 py-2 rounded">Download Full Log</button>
                    </form>
                </div>

                <pre class="whitespace-pre-wrap bg-gray-900 text-green-200 p-4 rounded">{{ $log }}</pre>
            </div>
        </div>
    </div>
</x-app-layout>
