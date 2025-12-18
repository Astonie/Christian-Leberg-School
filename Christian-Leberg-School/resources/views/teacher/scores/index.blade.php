<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Enter Scores — {{ $subject->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-green-800">{{ session('success') }}</div>
                @endif

                @if($components->count() === 0)
                    <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-800">No assessment components found for this subject. Create an assessment structure first.</div>
                @else
                    <form action="{{ route('teacher.scores.store', $subject) }}" method="POST">
                        @csrf
                        <div class="mb-4 flex items-center gap-4">
                            <label class="text-sm">Component</label>
                            <select name="component_id" class="border rounded px-2 py-1">
                                @foreach($components as $component)
                                    <option value="{{ $component->id }}" {{ $selected && $selected->id === $component->id ? 'selected' : '' }}>{{ $component->name }} (Max: {{ $component->max_score }})</option>
                                @endforeach
                            </select>
                            <button class="ml-4 bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Change</button>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">Admission</th>
                                    <th class="px-6 py-3 text-left">Student</th>
                                    <th class="px-6 py-3 text-left">Score</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $student)
                                    <tr>
                                        <td class="px-6 py-4">{{ $student->admission_number }}</td>
                                        <td class="px-6 py-4">{{ $student->user->name ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            <input type="number" name="scores[{{ $student->id }}]" step="0.01" min="0" max="{{ $selected->max_score ?? '' }}" value="{{ old('scores.'. $student->id, $scores[$student->id] ?? '') }}" class="border rounded px-2 py-1 w-32">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <button class="bg-green-600 text-white px-4 py-2 rounded">Save Scores</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>