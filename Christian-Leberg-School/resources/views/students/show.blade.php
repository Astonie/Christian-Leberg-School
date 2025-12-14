<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Profile') }}
        </h2>
    </x-slot>

    <!-- Main Container -->
    <div class="space-y-6">
        
        <!-- Top Card: Basic Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row gap-6 items-center md:items-start">
            <!-- Photo Placeholder -->
            <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center text-4xl text-gray-400 font-bold">
                {{ substr($student->user->name, 0, 1) }}
            </div>
            
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-bold text-gray-900">{{ $student->user->name }}</h3>
                <p class="text-gray-500">{{ $student->user->email }}</p>
                <div class="mt-4 flex flex-wrap justify-center md:justify-start gap-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        Class: {{ optional($student->currentStream)->full_name ?? 'N/A' }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        Adm No: {{ $student->admission_number }}
                    </span>
                     <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $student->gender == 'female' ? 'bg-pink-100 text-pink-800' : 'bg-indigo-100 text-indigo-800' }}">
                        {{ ucfirst($student->gender) }}
                    </span>
                </div>
            </div>
            
             <a href="{{ route('students.edit', $student) }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">Edit Profile</a>
        </div>

        <!-- Grid for Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Academic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Academic Details</h4>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Admission Date</span>
                        <span class="font-medium">{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M, Y') : 'N/A' }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Current Stream</span>
                        <span class="font-medium">{{ optional($student->currentStream)->name ?? '-' }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Enrollment Year</span>
                        <span class="font-medium">{{ optional($student->currentStream)->academicYear->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Personal Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Personal Details</h4>
                <div class="space-y-4">
                     <div class="flex justify-between">
                        <span class="text-gray-500">Date of Birth</span>
                        <span class="font-medium">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M, Y') : 'N/A' }}</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Age</span>
                        <span class="font-medium">
                            {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->age . ' years' : '-' }}
                        </span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500">Address</span>
                        <span class="font-medium">{{ 'N/A' }}</span> <!-- Address usually in Guardian or User? Schema check needed if added to student -->
                    </div>
                </div>
            </div>

             <!-- Guardian Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                <h4 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Guardian Information</h4>
                
                @if($student->guardians->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($student->guardians as $guardian)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $guardian->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $guardian->relationship }}</p>
                                    </div>
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Primary</span>
                                </div>
                                <div class="mt-3 space-y-1 text-sm">
                                    <p class="text-gray-600"><span class="font-medium">Phone:</span> {{ $guardian->phone_number }}</p>
                                    <p class="text-gray-600"><span class="font-medium">Email:</span> {{ $guardian->user->email }}</p>
                                    <p class="text-gray-600"><span class="font-medium">Address:</span> {{ $guardian->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic">No guardian assigned.</p>
                @endif
            </div>

        </div>

    </div>
</x-app-layout>
