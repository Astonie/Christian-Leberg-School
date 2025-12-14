<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Card: {{ $student->user->name }} - {{ $exam->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-semibold">Student Details</h3>
                        <p>{{ $student->user->name }} ({{ $student->admission_number }})</p>
                        <p>Class: {{ optional($student->current_stream)->schoolClass->name ?? '-' }} - {{ optional($student->current_stream)->name ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <p><strong>Exam:</strong> {{ $exam->name }}</p>
                        <p><strong>Term:</strong> {{ $exam->term }}</p>
                    </div>
                </div>

                <h4 class="font-semibold mb-2">Subjects and Marks</h4>
                <table class="min-w-full divide-y divide-gray-200 mb-4">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Subject</th>
                            <th class="px-4 py-2 text-left">Marks</th>
                            <th class="px-4 py-2 text-left">Grade</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($results as $r)
                            <tr>
                                <td class="px-4 py-2">{{ $r->subject->name }}</td>
                                <td class="px-4 py-2">{{ $r->marks }}</td>
                                <td class="px-4 py-2">{{ $r->grade }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    <p><strong>Total:</strong> {{ $total }}</p>
                    <p><strong>Average:</strong> {{ number_format($average, 2) }}</p>
                    <p><strong>Overall Grade:</strong> @if($average >= 80) A @elseif($average >=70) B @elseif($average >=60) C @elseif($average>=50) D @else E @endif</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
