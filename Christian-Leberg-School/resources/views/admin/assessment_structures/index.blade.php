<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assessment Structures</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-green-800">{{ session('success') }}</div>
                @endif
                @if(session('warning'))
                    <div class="mb-4 rounded-md bg-yellow-50 border border-yellow-200 p-4 text-yellow-800">{{ session('warning') }}</div>
                @endif

                <p class="text-sm text-gray-600 mb-4">Assessment structures define how subjects are assessed (components, weights, groups). Use these to configure grading and score entry.</p>

                @if($structures->isEmpty())
                    <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-800">No assessment structures found.</div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($structures as $s)
                                <tr>
                                    <td class="px-6 py-4">{{ $s->name }}</td>
                                    <td class="px-6 py-4">{{ $s->subject?->name ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $s->version }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <!-- edit/delete links can be added later -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>