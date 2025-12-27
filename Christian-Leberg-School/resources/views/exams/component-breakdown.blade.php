<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Component Breakdown - {{ $exam->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Exam Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-2">{{ $exam->name }}</h3>
                    <div class="text-sm text-gray-600">
                        <p>Academic Year: {{ $exam->academicYear->name }}</p>
                        <p>Term: {{ $exam->term->name }}</p>
                        @if($exam->assessmentStructure)
                            <p>Assessment Structure: {{ $exam->assessmentStructure->name }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filter by Student/Class -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Select Student</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                            <select id="studentFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ $selectedStudentId == $student->id ? 'selected' : '' }}>
                                        {{ $student->admission_number }} - {{ $student->first_name }} {{ $student->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Component Breakdown -->
            @if($selectedStudent)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">
                            Component Breakdown for {{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}
                        </h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        @if($exam->assessmentStructure)
                                            @foreach($exam->assessmentStructure->components->sortBy('order') as $component)
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ $component->name }}<br>
                                                    <span class="text-xs font-normal">({{ $component->weight }}%)</span>
                                                </th>
                                            @endforeach
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Mark</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($results as $result)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $result->subject->name }}
                                            </td>
                                            
                                            @if($exam->assessmentStructure && $result->is_computed && $result->component_breakdown)
                                                @foreach($exam->assessmentStructure->components->sortBy('order') as $component)
                                                    @php
                                                        $breakdown = collect($result->component_breakdown)->firstWhere('component_id', $component->id);
                                                    @endphp
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($breakdown)
                                                            <div>
                                                                <span class="font-semibold">{{ number_format($breakdown['raw'] ?? 0, 1) }}</span>
                                                                <span class="text-xs text-gray-500">/ {{ $breakdown['max'] }}</span>
                                                            </div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ number_format($breakdown['normalized'] ?? 0, 1) }}%
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            @elseif($exam->assessmentStructure)
                                                @foreach($exam->assessmentStructure->components as $component)
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                                        Not computed
                                                    </td>
                                                @endforeach
                                            @endif
                                            
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                {{ number_format($result->marks, 1) }}%
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $result->grade == 'A' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $result->grade == 'B' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $result->grade == 'C' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $result->grade == 'D' ? 'bg-orange-100 text-orange-800' : '' }}
                                                    {{ $result->grade == 'E' || $result->grade == 'F' ? 'bg-red-100 text-red-800' : '' }}">
                                                    {{ $result->grade }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($results->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                No results found for this student.
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                    Please select a student to view their component breakdown.
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('studentFilter').addEventListener('change', function() {
            const url = new URL(window.location.href);
            if (this.value) {
                url.searchParams.set('student_id', this.value);
            } else {
                url.searchParams.delete('student_id');
            }
            window.location.href = url.toString();
        });
    </script>
</x-app-layout>
