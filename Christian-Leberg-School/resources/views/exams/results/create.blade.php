@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Enter Results for {{ $exam->name }}</h2>

    <form method="POST" action="{{ route('exams.results.store', $exam) }}">
        @csrf
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->user->name }}</td>
                        <td>
                            <input type="hidden" name="results[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                            <input type="number" name="results[{{ $loop->index }}][marks]" class="border rounded px-2 py-1" min="0">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save Results</button>
        </div>
    </form>
</div>
@endsection
