<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Streams</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($streams->isEmpty())
                        <p class="text-gray-600">You are not assigned to any streams for the active academic year.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($streams as $stream)
                                <div class="border rounded p-4 flex items-start justify-between">
                                    <div>
                                        <div class="font-medium">{{ $stream->schoolClass->name ?? 'Class' }} - {{ $stream->name }}</div>
                                        <div class="text-sm text-gray-600">Students: {{ $stream->students->count() }}</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('students.index') }}?stream_id={{ $stream->id }}" class="px-3 py-1 bg-blue-100 text-blue-800 rounded">View Students</a>
                                        <a href="{{ route('attendance.index') }}?stream_id={{ $stream->id }}" class="px-3 py-1 bg-green-600 text-white rounded">Take Attendance</a>
                                        <a href="{{ route('admin.teachers.assignments.edit', ['teacher' => auth()->user()->teacher, 'highlight_stream' => $stream->id]) }}" class="px-3 py-1 bg-gray-100 border rounded">Edit Assignment</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
