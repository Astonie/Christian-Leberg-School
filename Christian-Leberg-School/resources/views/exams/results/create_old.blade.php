<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Enter Results for {{ $exam->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('exams.results.store', $exam) }}">
                @csrf
                <div class="mb-4 flex items-center gap-4">
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                        <select id="class_id" name="class_id" onchange="if(this.value){ window.location='?class_id='+this.value }" class="mt-1 block rounded-md border-gray-300 shadow-sm">
                            <option value="">All</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ (isset($classId) && $classId == $class->id) ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                    <select name="subject_id" id="subject_id" class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm">
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <x-alerts />
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marks</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" name="results[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                            <input type="number" name="results[{{ $loop->index }}][marks]" class="border rounded px-2 py-1 w-24" min="0" required>
                                            @error("results.$loop->index.marks")
                                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Results</button>
                        </div>
                    </div>
                </div>
            </form>
                
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="font-semibold mb-2">Import Results via CSV</h4>
                    <p class="text-sm text-gray-500 mb-2">CSV columns: <strong>admission_number, subject_code (or subject_id), marks, remarks</strong>. You may also select a subject to apply to all rows.</p>
                    <form action="{{ route('exams.results.import', $exam) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Subject (optional)</label>
                            <select name="subject_id" class="mt-1 block rounded-md border-gray-300 shadow-sm w-1/3">
                                <option value="">(use subject_code column in CSV)</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">CSV File</label>
                            <input type="file" name="file" accept=".csv,text/csv" class="mt-2">
                        </div>
                        <div>
                            <button class="px-4 py-2 bg-green-600 text-white rounded">Import CSV</button>
                            <a href="{{ route('exams.results.sample_csv', $exam) }}" class="ml-3 px-3 py-2 bg-gray-100 border rounded text-sm">Download sample CSV</a>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</x-app-layout>
