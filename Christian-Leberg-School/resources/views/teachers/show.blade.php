<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $teacher->user->name }}</h3>
                            <p class="text-gray-500">{{ $teacher->qualification }} - {{ $teacher->specialization }}</p>
                        </div>
                        <a href="{{ route('teachers.edit', $teacher) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Teacher
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Personal Info -->
                        <div>
                            <h3 class="font-semibold text-lg border-b pb-2 mb-2">Contact Information</h3>
                            <p><strong>Email:</strong> {{ $teacher->user->email }}</p>
                            <p><strong>Phone:</strong> {{ $teacher->phone_number }}</p>
                            <p><strong>Address:</strong> {{ $teacher->address ?? 'N/A' }}</p>
                        </div>
                        
                        <!-- Employment Info -->
                        <div>
                            <h3 class="font-semibold text-lg border-b pb-2 mb-2">Employment Details</h3>
                            <p><strong>Employee Number:</strong> {{ $teacher->employee_number }}</p>
                            <p><strong>Hire Date:</strong> {{ $teacher->hire_date?->format('F j, Y') }}</p>
                            <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $teacher->employment_type)) }}</p>
                            <p><strong>Status:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $teacher->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($teacher->status) }}</span></p>
                        </div>
                    </div>

                    <!-- Subjects Taught -->
                    <div class="mt-8">
                        <h3 class="font-semibold text-lg border-b pb-2 mb-4">Subjects Taught</h3>
                        @if($teacher->subjects->count() > 0)
                            <ul class="list-disc pl-5">
                                @foreach($teacher->subjects as $subject)
                                    <li>{{ $subject->name }} ({{ $subject->code }})</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">No subjects assigned yet.</p>
                        @endif
                        
                        <!-- Link to assign subjects -->
                        <div class="mt-4">
                             <a href="{{ route('teachers.subjects.index', $teacher) }}" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded border border-gray-300">
                                Manage Subjects
                             </a>
                                      <a href="{{ route('admin.teachers.assignments.edit', $teacher) }}" class="ml-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded border border-gray-300">
                                          Manage Assignments
                                      </a>
                        </div>
                    </div>

                    <!-- Classes Managed -->
                    <div class="mt-8">
                        <h3 class="font-semibold text-lg border-b pb-2 mb-4">Classes / Streams Managed</h3>
                        @if($teacher->streams->count() > 0)
                             <ul class="list-disc pl-5">
                                @foreach($teacher->streams as $stream)
                                    <li>
                                        {{ $stream->full_name }}
                                        @if($stream->pivot->is_class_teacher)
                                            <span class="text-xs font-bold bg-blue-100 text-blue-800 px-2 py-0.5 rounded ml-2">Class Teacher</span>
                                        @endif
                                        <a href="{{ route('admin.teachers.assignments.edit', ['teacher' => $teacher, 'highlight_stream' => $stream->id]) }}" class="ml-3 text-sm text-blue-600 hover:underline">Edit Assignment</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">No classes assigned yet.</p>
                        @endif
                    </div>

                    <!-- Assigned Subject Summary -->
                    <div class="mt-8">
                        <h3 class="font-semibold text-lg border-b pb-2 mb-4">Assigned Subject Summary</h3>
                        @if(!empty($streamAssignments) && count($streamAssignments) > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stream</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class Teacher</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($streamAssignments as $sa)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sa['stream']->full_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sa['subject']?->name ?? '—' }} {{ $sa['subject']?->code ? '(' . $sa['subject']->code . ')' : '' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if($sa['is_class_teacher'])
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Yes</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-700">No</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-gray-500 italic">No assignments configured yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
