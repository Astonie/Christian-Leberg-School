<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold">{{ $student->user->name }}</h1>
                            <p class="text-gray-500">{{ $student->admission_number }}</p>
                        </div>
                        <div>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <h3 class="font-semibold text-lg border-b pb-2 mb-2">Personal Information</h3>
                            <p><strong>Email:</strong> {{ $student->user->email }}</p>
                            <p><strong>Date of Birth:</strong> {{ $student->date_of_birth->format('F j, Y') }} ({{ $student->date_of_birth->age }} years)</p>
                            <p><strong>Gender:</strong> {{ ucfirst($student->gender) }}</p>
                            <p><strong>Nationality:</strong> {{ $student->nationality }}</p>
                            <p><strong>Address:</strong> {{ $student->address }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg border-b pb-2 mb-2">Academic Information</h3>
                            <p><strong>Admission Date:</strong> {{ $student->admission_date->format('F j, Y') }}</p>
                            <p><strong>Current Class:</strong> 
                                @if($student->current_stream)
                                    {{ $student->current_stream->class->name ?? '' }} - {{ $student->current_stream->name ?? '' }}
                                @else
                                    Not Assigned
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
