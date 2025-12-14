@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">Results for {{ $exam->name }}</h2>

    <table class="w-full table-auto">
        <thead>
            <tr>
                <th>Student</th>
                <th>Marks</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $res)
                <tr>
                    <td>{{ $res->student->user->name }}</td>
                    <td>{{ $res->marks }}</td>
                    <td>{{ $res->grade }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
