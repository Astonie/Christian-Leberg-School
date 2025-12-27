<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Bulk Marks Import/Export</h2>
                <p class="text-sm text-gray-600 mt-2">Import or export examination marks for up to 700 students using CSV files</p>
            </div>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <!-- Selection Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                <h3 class="text-lg font-bold text-blue-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Select Parameters
                </h3>
            </div>

            <div class="p-6">
                <form id="paramForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Exam Selection -->
                        <div>
                            <label for="exam_id" class="block text-sm font-bold text-gray-900 mb-2">
                                Examination <span class="text-red-500">*</span>
                            </label>
                            <select name="exam_id" id="exam_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Examination</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->term->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject Selection -->
                        <div>
                            <label for="subject_id" class="block text-sm font-bold text-gray-900 mb-2">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <select name="subject_id" id="subject_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stream Selection -->
                        <div>
                            <label for="stream_id" class="block text-sm font-bold text-gray-900 mb-2">
                                Stream/Class <span class="text-red-500">*</span>
                            </label>
                            <select name="stream_id" id="stream_id" required
                                class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="">Select Stream</option>
                                @foreach($streams as $stream)
                                    <option value="{{ $stream->id }}">{{ $stream->schoolClass->name }} - {{ $stream->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Download Template -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                    <h3 class="text-lg font-bold text-green-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Template
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-4">Download a CSV template pre-filled with student information. Fill in the marks and upload to import.</p>
                    <form method="GET" action="{{ route('exam-marks.template') }}" onsubmit="return validateParams()">
                        <input type="hidden" name="exam_id" id="template_exam_id">
                        <input type="hidden" name="subject_id" id="template_subject_id">
                        <input type="hidden" name="stream_id" id="template_stream_id">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download Template
                        </button>
                    </form>
                </div>
            </div>

            <!-- Export Existing Marks -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                    <h3 class="text-lg font-bold text-purple-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Export Existing Marks
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-4">Export currently entered marks to a CSV file for offline editing or record keeping.</p>
                    <form method="POST" action="{{ route('exam-marks.export') }}" onsubmit="return validateParams()">
                        @csrf
                        <input type="hidden" name="exam_id" id="export_exam_id">
                        <input type="hidden" name="subject_id" id="export_subject_id">
                        <input type="hidden" name="stream_id" id="export_stream_id">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Export Marks
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Upload CSV for Import -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mt-6">
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                <h3 class="text-lg font-bold text-orange-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    Upload CSV File
                </h3>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('exam-marks.preview') }}" enctype="multipart/form-data" onsubmit="return validateUpload()">
                    @csrf
                    <input type="hidden" name="exam_id" id="upload_exam_id">
                    <input type="hidden" name="subject_id" id="upload_subject_id">
                    <input type="hidden" name="stream_id" id="upload_stream_id">

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-900 mb-3">CSV File <span class="text-red-500">*</span></label>
                        <input type="file" name="csv_file" accept=".csv" required
                            class="block w-full text-sm text-gray-600
                                file:mr-4 file:py-3 file:px-6
                                file:rounded-xl file:border-0
                                file:text-sm file:font-semibold
                                file:bg-orange-50 file:text-orange-700
                                hover:file:bg-orange-100
                                cursor-pointer border-2 border-gray-300 rounded-xl
                                focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 mb-6">
                        <h4 class="font-bold text-blue-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Instructions
                        </h4>
                        <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                            <li>Download the template first to get the correct format</li>
                            <li>Fill in marks (0-100) in the "marks" column</li>
                            <li>Grades will be auto-calculated but can be manually set</li>
                            <li>Leave marks blank to skip a student</li>
                            <li>Do not modify student_id, admission_number, or name columns</li>
                            <li>File size limit: 10MB</li>
                        </ul>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview & Validate
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Sync form parameters
        document.getElementById('exam_id').addEventListener('change', syncParams);
        document.getElementById('subject_id').addEventListener('change', syncParams);
        document.getElementById('stream_id').addEventListener('change', syncParams);

        function syncParams() {
            const examId = document.getElementById('exam_id').value;
            const subjectId = document.getElementById('subject_id').value;
            const streamId = document.getElementById('stream_id').value;

            // Template form
            document.getElementById('template_exam_id').value = examId;
            document.getElementById('template_subject_id').value = subjectId;
            document.getElementById('template_stream_id').value = streamId;

            // Export form
            document.getElementById('export_exam_id').value = examId;
            document.getElementById('export_subject_id').value = subjectId;
            document.getElementById('export_stream_id').value = streamId;

            // Upload form
            document.getElementById('upload_exam_id').value = examId;
            document.getElementById('upload_subject_id').value = subjectId;
            document.getElementById('upload_stream_id').value = streamId;
        }

        function validateParams() {
            const examId = document.getElementById('exam_id').value;
            const subjectId = document.getElementById('subject_id').value;
            const streamId = document.getElementById('stream_id').value;

            if (!examId || !subjectId || !streamId) {
                alert('Please select Examination, Subject, and Stream first.');
                return false;
            }
            return true;
        }

        function validateUpload() {
            if (!validateParams()) return false;

            const fileInput = document.querySelector('input[name="csv_file"]');
            if (!fileInput.files.length) {
                alert('Please select a CSV file to upload.');
                return false;
            }

            return true;
        }
    </script>
    @endpush
</x-app-layout>
