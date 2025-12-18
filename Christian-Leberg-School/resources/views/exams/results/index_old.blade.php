<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Results for {{ $exam->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <x-alerts />
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm text-gray-600">Showing results for <strong>{{ $exam->name }}</strong></div>
                        <form method="GET" action="{{ route('exams.results.export', $exam) }}" class="flex items-center gap-2">
                            <select name="subject_id" class="rounded border-gray-300">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ (isset($selectedSubject) && $selectedSubject == $subject->id) ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <select name="class_id" class="rounded border-gray-300">
                                <option value="">All Classes</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ (isset($selectedClass) && $selectedClass == $class->id) ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <button class="px-3 py-1 bg-green-600 text-white rounded">Export CSV</button>
                        </form>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($results as $res)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $res->student->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('exam-results.update', $res) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="marks" value="{{ $res->marks }}" min="0" max="100" class="border rounded px-2 py-1 w-20">
                                            <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $res->grade }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <!-- optional: delete or remarks -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
