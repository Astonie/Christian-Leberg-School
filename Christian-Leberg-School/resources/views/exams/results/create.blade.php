<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Enter Results for {{ $exam->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('exams.results.store', $exam) }}">
                @csrf
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
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save Results</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
