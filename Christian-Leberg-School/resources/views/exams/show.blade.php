<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Exam: {{ $exam->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-2">Details</h3>
                <p><strong>Academic Year:</strong> {{ $exam->academicYear->name ?? '' }}</p>
                <p><strong>Term:</strong> {{ $exam->term }}</p>
                <p><strong>Dates:</strong> {{ $exam->start_date->format('Y-m-d') }} - {{ $exam->end_date->format('Y-m-d') }}</p>

                <div class="mt-4">
                    <a href="{{ route('exams.results.index', $exam) }}" class="px-3 py-2 bg-green-600 text-white rounded">View Results</a>
                    <a href="{{ route('exams.results.create', $exam) }}" class="ml-2 px-3 py-2 bg-indigo-600 text-white rounded">Enter Results</a>
                    @if(auth()->user()->hasRole('teacher'))
                        @foreach(auth()->user()->teacher->subjects()->wherePivot('academic_year_id', $exam->academic_year_id)->get() as $subj)
                            <a href="{{ route('exams.results.create_for_subject', [$exam, $subj]) }}" class="ml-2 px-3 py-2 bg-indigo-500 text-white rounded">Enter {{ $subj->name }} Results</a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
