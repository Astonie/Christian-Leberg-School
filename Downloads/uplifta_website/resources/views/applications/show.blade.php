@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8">Loan Application Details</h1>
    <div class="bg-white rounded-lg shadow p-8">
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="font-semibold text-lg mb-2">Applicant</h2>
                <p><span class="font-medium">Name:</span> {{ $application->first_name }} {{ $application->last_name }}</p>
                <p><span class="font-medium">Email:</span> {{ $application->email }}</p>
                <p><span class="font-medium">Phone:</span> {{ $application->phone }}</p>
                <p><span class="font-medium">Country:</span> {{ $application->country }}</p>
                <p><span class="font-medium">Address:</span> {{ $application->address }}</p>
            </div>
            <div>
                <h2 class="font-semibold text-lg mb-2">Business</h2>
                <p><span class="font-medium">Name:</span> {{ $application->business_name }}</p>
                <p><span class="font-medium">Type:</span> {{ $application->business_type }}</p>
                <p><span class="font-medium">Years in Business:</span> {{ $application->years_in_business }}</p>
                <p><span class="font-medium">Employees:</span> {{ $application->employees }}</p>
                <p><span class="font-medium">Monthly Revenue:</span> ${{ number_format($application->monthly_revenue, 2) }}</p>
            </div>
        </div>
        <div class="mb-6">
            <h2 class="font-semibold text-lg mb-2">Loan Details</h2>
            <p><span class="font-medium">Amount:</span> ${{ number_format($application->loan_amount, 2) }}</p>
            <p><span class="font-medium">Term:</span> {{ $application->loan_term }} months</p>
            <p><span class="font-medium">Purpose:</span> {{ $application->loan_purpose }}</p>
            <p><span class="font-medium">Description:</span> {{ $application->loan_description }}</p>
            <p><span class="font-medium">Repayment Plan:</span> {{ $application->repayment_plan }}</p>
        </div>
        <div class="flex gap-4">
            <form action="{{ route('applications.destroy', $application) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm('Delete this application?')">Delete</button>
            </form>
            <a href="{{ route('admin.applications.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Back to List</a>
        </div>
    </div>
</div>
@endsection
