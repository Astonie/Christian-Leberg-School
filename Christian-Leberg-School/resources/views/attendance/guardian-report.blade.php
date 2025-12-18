<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Children\'s Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if($students->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <p class="text-yellow-800">No children found in your guardian profile.</p>
                </div>
            @else
                <!-- Child Selector -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Select Child</h3>
                        
                        <div class="flex flex-wrap gap-2">
                            @foreach($students as $student)
                                <a href="{{ route('attendance.guardian-report', $student->id) }}" 
                                   class="px-4 py-2 rounded-lg border {{ $selectedStudent && $selectedStudent->id === $student->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                                    {{ $student->user->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($selectedStudent)
                    <!-- Missing Records Alerts -->
                    @if(!empty($missingRecords))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-lg font-bold text-red-800">Missing Attendance Records</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p class="mb-2">The following subjects have missing or incomplete attendance records for {{ $selectedStudent->user->name }}:</p>
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach($missingRecords as $missing)
                                                <li><strong>{{ $missing['subject']->name }}</strong> - {{ $missing['message'] }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Student Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Student Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500">Name:</span>
                                    <p class="font-medium text-gray-900">{{ $selectedStudent->user->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Admission Number:</span>
                                    <p class="font-medium text-gray-900">{{ $selectedStudent->admission_number }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Date of Birth:</span>
                                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($selectedStudent->date_of_birth)->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Statistics by Subject -->
                    @if(!empty($attendanceData))
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Attendance Summary (Last 30 Days)</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($attendanceData as $data)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <h4 class="font-bold text-gray-900 mb-3">{{ $data['subject']->name }}</h4>
                                            
                                            <!-- Attendance Rate -->
                                            <div class="mb-3">
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-sm text-gray-600">Attendance Rate</span>
                                                    <span class="text-sm font-bold {{ $data['attendance_rate'] >= 90 ? 'text-green-600' : ($data['attendance_rate'] >= 75 ? 'text-yellow-600' : 'text-red-600') }}">
                                                        {{ $data['attendance_rate'] }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="h-2 rounded-full {{ $data['attendance_rate'] >= 90 ? 'bg-green-600' : ($data['attendance_rate'] >= 75 ? 'bg-yellow-600' : 'bg-red-600') }}" 
                                                         style="width: {{ $data['attendance_rate'] }}%"></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Statistics -->
                                            <div class="space-y-1 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Total:</span>
                                                    <span class="font-medium">{{ $data['total'] }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-green-600">Present:</span>
                                                    <span class="font-medium">{{ $data['present'] }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-red-600">Absent:</span>
                                                    <span class="font-medium">{{ $data['absent'] }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-yellow-600">Late:</span>
                                                    <span class="font-medium">{{ $data['late'] }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-blue-600">Excused:</span>
                                                    <span class="font-medium">{{ $data['excused'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Recent Attendance Details -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Attendance Details</h3>
                                
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($attendanceData as $data)
                                                @foreach($data['records']->take(5) as $record)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $record->subject->name }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            @if($record->status === 'present')
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                    Present
                                                                </span>
                                                            @elseif($record->status === 'absent')
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                    Absent
                                                                </span>
                                                            @elseif($record->status === 'late')
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                    Late
                                                                </span>
                                                            @else
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                    Excused
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-500">
                                                            {{ $record->remarks ?? '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <p class="text-yellow-800">No attendance records found for {{ $selectedStudent->user->name }} in the last 30 days.</p>
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
