<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium">Welcome, {{ auth()->user()->name }}</h3>
                            <p class="text-gray-600">Access your classes, mark attendance, and manage student performance.</p>
                        </div>
                        <div>
                            <form method="GET" action="{{ route('dashboard.teacher') }}" class="flex items-center gap-2">
                                <label class="text-sm text-gray-600">Exam:</label>
                                <select name="exam_id" onchange="this.form.submit()" class="border rounded px-2 py-1">
                                    <option value="">Latest</option>
                                    @foreach($exams as $ex)
                                        <option value="{{ $ex->id }}" {{ (isset($selectedExam) && $selectedExam && $selectedExam->id == $ex->id) ? 'selected' : '' }}>{{ $ex->name }} ({{ $ex->term }})</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Attendance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer" onclick="window.location='{{ route('attendance.index') }}'">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daily Attendance</h3>
                         <span class="text-blue-600 bg-blue-100 p-2 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Mark and view attendance for your assigned streams.</p>
                     <a href="{{ route('attendance.index') }}" class="text-blue-600 font-medium hover:text-blue-800 flex items-center">
                        Take Attendance &rarr;
                    </a>
                </div>
                <!-- My Subjects -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">My Subjects</h3>
                    </div>
                    @if($subjects->isEmpty())
                        <p class="text-sm text-gray-600">You have no subjects assigned for the active academic year.</p>
                    @else
                        <ul class="list-disc list-inside text-sm text-gray-700">
                            @foreach($subjects as $subject)
                                <li>{{ $subject->name }} ({{ $subject->code }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- My Streams -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">My Streams</h3>
                    </div>
                    @if($streams->isEmpty())
                        <p class="text-sm text-gray-600">You are not assigned to any streams for the active academic year.</p>
                    @else
                        <div class="space-y-3 text-sm text-gray-700">
                            @foreach($streams as $stream)
                                <div class="border rounded p-3 flex items-start justify-between">
                                    <div>
                                        <div class="font-medium">{{ $stream->schoolClass->name ?? 'Class' }} - {{ $stream->name }}</div>
                                        <div class="mt-2 text-xs text-gray-600">
                                            @php
                                                $assignedSubjectId = $stream->pivot->subject_id ?? null;
                                                $assignedSubject = $assignedSubjectId ? \App\Models\Subject::find($assignedSubjectId) : null;
                                                $pendingAssigned = $assignedSubject ? ($pending[$stream->id][$assignedSubject->id] ?? 0) : 0;
                                            @endphp

                                            @if($assignedSubject)
                                                <div class="mb-1">Assigned: <strong>{{ $assignedSubject->name }} ({{ $assignedSubject->code }})</strong>
                                                    @if($pendingAssigned)
                                                        <span class="ml-2 inline-block bg-red-100 text-red-800 text-xs px-2 rounded-full">{{ $pendingAssigned }}</span>
                                                    @endif
                                                </div>
                                                <div class="mt-1">
                                                    <a href="{{ route('teacher.export.missing_results') }}?exam_id={{ $exam->id }}&stream_id={{ $stream->id }}&subject_id={{ $assignedSubject->id }}" class="text-sm text-gray-700 bg-gray-100 px-2 py-1 rounded border">Export Missing Results</a>
                                                </div>
                                            @endif

                                            @if($subjects->count())
                                                <div class="text-xs text-gray-600">Other subjects: 
                                                    @foreach($subjects as $subject)
                                                        @if($subject->id != $assignedSubjectId)
                                                            @php $pendingCount = $pending[$stream->id][$subject->id] ?? 0; @endphp
                                                            <a href="{{ route('exams.results.create_for_subject', ['exam' => $exam->id, 'subject' => $subject->id]) }}?stream_id={{ $stream->id }}" class="text-blue-600 hover:underline text-sm mr-2">{{ $subject->code ?? $subject->name }} @if($pendingCount)<span class="ml-1 inline-block bg-red-100 text-red-800 text-xs px-2 rounded-full">{{ $pendingCount }}</span>@endif</a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-sm flex flex-col items-end gap-2">
                                        @if($assignedSubject)
                                            <a href="{{ route('exams.results.create_for_subject', ['exam' => $exam->id, 'subject' => $assignedSubject->id]) }}?stream_id={{ $stream->id }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Enter Results</a>
                                        @else
                                            <a href="{{ route('exams.results.index', $exam) }}?class_id={{ $stream->class_id }}" class="px-3 py-1 bg-gray-200 text-gray-800 rounded">Enter Results</a>
                                        @endif
                                        <a href="{{ route('attendance.index') }}?stream_id={{ $stream->id }}" class="px-3 py-1 bg-green-600 text-white rounded">Take Attendance</a>
                                        @if($stream->pivot->is_class_teacher)
                                            <a href="{{ route('students.index') }}?stream_id={{ $stream->id }}" class="px-3 py-1 bg-blue-100 text-blue-800 rounded">Manage Class</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Quick Enter Results -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Enter Results</h3>
                    </div>
                    @if($subjects->isEmpty() || $streams->isEmpty() || $exams->isEmpty())
                        <p class="text-sm text-gray-600">Ensure you have an active exam, subjects, and streams assigned to enter results.</p>
                    @else
                        <form id="enter-results-form" onsubmit="return false;" class="flex items-center gap-2">
                            <select id="er-exam" class="rounded border-gray-300">
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->term }})</option>
                                @endforeach
                            </select>
                            <select id="er-subject" class="rounded border-gray-300">
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                @endforeach
                            </select>
                            <select id="er-stream" class="rounded border-gray-300">
                                @foreach($streams as $stream)
                                    <option value="{{ $stream->id }}">{{ $stream->schoolClass->name ?? 'Class' }} - {{ $stream->name }}</option>
                                @endforeach
                            </select>
                            <button id="er-go" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Enter Results</button>
                        </form>
                        <script>
                            document.getElementById('er-go').addEventListener('click', function () {
                                var exam = document.getElementById('er-exam').value;
                                var subject = document.getElementById('er-subject').value;
                                var stream = document.getElementById('er-stream').value;
                                if (!exam || !subject) return;
                                // Redirect to the teacher-specific create page for the exam and subject, include stream_id query
                                window.location = '/exams/' + exam + '/subjects/' + subject + '/results/create?stream_id=' + stream;
                            });
                        </script>
                    @endif
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
