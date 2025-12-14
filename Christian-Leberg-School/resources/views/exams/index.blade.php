<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Exams</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">All Exams</h3>
                    <a href="{{ route('exams.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Create Exam</a>
                </div>

                @if($exams->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($exams as $exam)
                                <tr>
                                    <td class="px-6 py-4">{{ $exam->name }}</td>
                                    <td class="px-6 py-4">{{ $exam->academicYear->name ?? '' }}</td>
                                    <td class="px-6 py-4">{{ $exam->term }}</td>
                                    <td class="px-6 py-4">{{ $exam->start_date->format('Y-m-d') }} - {{ $exam->end_date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('exams.show', $exam) }}" class="text-indigo-600 mr-3">View</a>
                                        <a href="{{ route('exams.results.create', $exam) }}" class="text-green-600">Enter Results</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">{{ $exams->links() }}</div>
                @else
                    <p class="text-gray-500">No exams yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
