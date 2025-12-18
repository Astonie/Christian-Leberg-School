<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Assignments for {{ $teacher->user->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.teachers.assignments.update', $teacher) }}">
                    @csrf

                    <p class="text-sm text-gray-600 mb-4">Assign a subject to each stream the teacher will teach this academic year ({{ $year?->name ?? 'N/A' }}).</p>

                    <table class="min-w-full divide-y divide-gray-200 mb-4">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium">Stream</th>
                                <th class="px-4 py-2 text-left text-sm font-medium">Assign Subject</th>
                                <th class="px-4 py-2 text-left text-sm font-medium">Class Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($streams as $stream)
                                @php $isHighlighted = isset($highlightStream) && (int)$highlightStream === (int)$stream->id; @endphp
                                <tr id="stream-row-{{ $stream->id }}" class="{{ $isHighlighted ? 'ring-2 ring-blue-300 bg-blue-50' : '' }}">
                                    <td class="px-4 py-2 text-sm">{{ $stream->full_name }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <select name="assignments[{{ $stream->id }}]" class="rounded border-gray-300">
                                            <option value="">-- Not Assigned --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ (isset($assigned[$stream->id]) && $assigned[$stream->id] == $subject->id) ? 'selected' : '' }}>{{ $subject->name }} ({{ $subject->code }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        <input type="checkbox" name="class_teacher[{{ $stream->id }}]" value="1" {{ isset($classTeachers[$stream->id]) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if(isset($highlightStream))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var el = document.getElementById('stream-row-{{ $highlightStream }}');
                                if (el) {
                                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            });
                        </script>
                    @endif

                    <div class="flex items-center gap-2">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Assignments</button>
                        <a href="{{ route('teachers.show', $teacher) }}" class="px-3 py-2 bg-gray-100 border rounded">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
