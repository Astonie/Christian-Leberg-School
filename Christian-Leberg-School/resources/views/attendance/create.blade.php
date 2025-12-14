<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mark Attendance: {{ $stream->schoolClass->name }} - {{ $stream->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Date: {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h3>
                        <a href="{{ route('attendance.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Selection</a>
                    </div>

                    @if($students->isEmpty())
                        <div class="p-4 bg-yellow-50 text-yellow-700 rounded-md">
                            No students found in this stream.
                        </div>
                    @else
                        <form action="{{ route('attendance.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="stream_id" value="{{ $stream->id }}">
                            <input type="hidden" name="date" value="{{ $date }}">

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission No</th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($students as $index => $student)
                                            @php
                                                $record = $existingAttendance[$student->id] ?? null;
                                                $currentStatus = $record ? $record->status : 'present'; // Default to present
                                            @endphp
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $student->user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $student->admission_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="hidden" name="attendance[{{ $index }}][student_id]" value="{{ $student->id }}">
                                                    
                                                    <div class="flex justify-center space-x-4">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="attendance[{{ $index }}][status]" value="present" class="text-green-600 focus:ring-green-500" {{ $currentStatus == 'present' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700">Present</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="attendance[{{ $index }}][status]" value="absent" class="text-red-600 focus:ring-red-500" {{ $currentStatus == 'absent' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700">Absent</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="attendance[{{ $index }}][status]" value="late" class="text-yellow-600 focus:ring-yellow-500" {{ $currentStatus == 'late' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700">Late</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="attendance[{{ $index }}][status]" value="excused" class="text-blue-600 focus:ring-blue-500" {{ $currentStatus == 'excused' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700">Excused</span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <input type="text" name="attendance[{{ $index }}][remarks]" value="{{ $record->remarks ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Optional remarks">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-semibold shadow-sm">
                                    Save Attendance Records
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
