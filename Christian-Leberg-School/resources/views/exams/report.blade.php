<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Exam Report: {{ $exam->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($insufficient->count())
                    <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-300">
                        <h3 class="font-semibold">Students with insufficient subjects</h3>
                        <p class="text-sm text-gray-600">The following students have fewer than 4 subjects recorded for this exam. Please enter missing results before generating final reports.</p>
                        <ul class="mt-2 list-disc pl-6">
                            @foreach($insufficient as $s)
                                <li>{{ $s->user->name }} ({{ $s->admission_number }})</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h3 class="text-lg font-semibold mb-4">Report</h3>
                @if($report->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjects</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($report as $row)
                                <tr>
                                    <td class="px-6 py-4">{{ $row['student']->user->name }}</td>
                                    <td class="px-6 py-4">
                                        @foreach($row['results'] as $r)
                                            <div>{{ $r->subject->name }}: {{ $r->marks }} ({{ $r->grade }})</div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">{{ $row['total'] }}</td>
                                    <td class="px-6 py-4">{{ number_format($row['average'], 2) }}</td>
                                    <td class="px-6 py-4">{{ $row['grade'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">No complete reports available yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
