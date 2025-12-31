<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Class Report: {{ $class->name }} - {{ $exam->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Students Report</h3>
                    <a href="{{ route('exams.report.pdf', [$exam, $class]) }}" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Download PDF</a>
                </div>

                @if($students->count())
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjects/Marks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($students as $stu)
                                <tr>
                                    <td class="px-6 py-4">{{ $stu->user->name }}</td>
                                    <td class="px-6 py-4">
                                        @foreach($stu->examResults()->where('exam_id', $exam->id)->get() as $r)
                                            <div>{{ $r->subject->name }}: {{ $r->marks }}</div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">{{ number_format($stu->examResults()->where('exam_id', $exam->id)->avg('marks') ?? 0, 2) }}</td>
                                    <td class="px-6 py-4">{{ optional($stu->examResults()->where('exam_id', $exam->id)->first())->grade ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('exams.student-report.pdf', [$exam, $stu]) }}" class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Download</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">No students found for this class and academic year.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
