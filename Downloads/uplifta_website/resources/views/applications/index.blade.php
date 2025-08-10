@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8">Loan Applications</h1>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($applications as $app)
                <tr>
                    <td class="px-4 py-3">{{ $app->first_name }} {{ $app->last_name }}</td>
                    <td class="px-4 py-3">{{ $app->email }}</td>
                    <td class="px-4 py-3">{{ $app->business_name }}</td>
                    <td class="px-4 py-3">${{ number_format($app->loan_amount, 2) }}</td>
                    <td class="px-4 py-3">{{ $app->status ?? 'Pending' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('applications.show', $app) }}" class="text-blue-600 hover:underline mr-2">View</a>
                        <form action="{{ route('applications.destroy', $app) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete this application?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $applications->links() }}</div>
</div>
@endsection
