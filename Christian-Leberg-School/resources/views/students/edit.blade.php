<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Student') }}: {{ $student->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('students.update', $student) }}">
                        @csrf
                        @method('PUT')

                        <!-- User Info -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <?php 
                                $names = explode(' ', $student->user->name, 2);
                                $first_name = $names[0] ?? '';
                                $last_name = $names[1] ?? '';
                            ?>
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $first_name)" required />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $last_name)" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                            <div class="col-span-2">
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $student->user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Student Profile -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Student Profile</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="admission_number" :value="__('Admission Number')" />
                                <x-text-input id="admission_number" class="block mt-1 w-full" type="text" name="admission_number" :value="old('admission_number', $student->admission_number)" required />
                                <x-input-error :messages="$errors->get('admission_number')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="admission_date" :value="__('Admission Date')" />
                                <x-text-input id="admission_date" class="block mt-1 w-full" type="date" name="admission_date" :value="old('admission_date', $student->admission_date ? $student->admission_date->format('Y-m-d') : '')" required />
                                <x-input-error :messages="$errors->get('admission_date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '')" required />
                                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="gender" :value="__('Gender')" />
                                <select id="gender" name="gender" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nationality" :value="__('Nationality')" />
                                <x-text-input id="nationality" class="block mt-1 w-full" type="text" name="nationality" :value="old('nationality', $student->nationality)" />
                                <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="stream_id" :value="__('Class Stream')" />
                                <select id="stream_id" name="stream_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">No Assignment</option>
                                    @foreach ($classes as $class)
                                        <optgroup label="{{ $class->name }}">
                                            @foreach ($class->streams as $stream)
                                                <option value="{{ $stream->id }}" {{ (old('stream_id') ?? ($student->current_stream?->id)) == $stream->id ? 'selected' : '' }}>{{ $stream->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('stream_id')" class="mt-2" />
                            </div>
                            
                             <div class="col-span-2">
                                <x-input-label for="address" :value="__('Address')" />
                                <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('address', $student->address) }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                             <div class="col-span-2">
                                <x-input-label for="medical_conditions" :value="__('Medical Conditions')" />
                                <textarea id="medical_conditions" name="medical_conditions" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="2">{{ old('medical_conditions', $student->medical_conditions) }}</textarea>
                                <x-input-error :messages="$errors->get('medical_conditions')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Student') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
