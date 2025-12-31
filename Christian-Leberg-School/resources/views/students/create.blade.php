<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Student') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('students.store') }}">
                        @csrf

                        <!-- User Info -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                            <div class="col-span-2">
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Student Profile -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Student Profile</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="admission_number" :value="__('Admission Number (Optional)')" />
                                <x-text-input id="admission_number" class="block mt-1 w-full" type="text" name="admission_number" :value="old('admission_number')" placeholder="Leave blank for auto-generation" />
                                <p class="mt-1 text-sm text-gray-500">Leave blank to auto-generate (e.g., {{ now()->year }}-0001)</p>
                                <x-input-error :messages="$errors->get('admission_number')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="admission_date" :value="__('Admission Date')" />
                                <x-text-input id="admission_date" class="block mt-1 w-full" type="date" name="admission_date" :value="old('admission_date')" required />
                                <x-input-error :messages="$errors->get('admission_date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" required />
                                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="gender" :value="__('Gender')" />
                                <select id="gender" name="gender" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nationality" :value="__('Nationality')" />
                                <x-text-input id="nationality" class="block mt-1 w-full" type="text" name="nationality" :value="old('nationality')" />
                                <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="stream_id" :value="__('Assign to Class Stream')" />
                                <select id="stream_id" name="stream_id" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                    <option value="">No Assignment</option>
                                    @foreach ($classes as $class)
                                        <optgroup label="{{ $class->name }}">
                                            @foreach ($class->streams as $stream)
                                                <option value="{{ $stream->id }}" {{ old('stream_id') == $stream->id ? 'selected' : '' }}>{{ $stream->full_name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('stream_id')" class="mt-2" />
                            </div>
                            <div class="col-span-2">
                                <x-input-label for="address" :value="__('Address')" />
                                <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" rows="3">{{ old('address') }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                             <div class="col-span-2">
                                <x-input-label for="medical_conditions" :value="__('Medical Conditions (Optional)')" />
                                <textarea id="medical_conditions" name="medical_conditions" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" rows="2">{{ old('medical_conditions') }}</textarea>
                                <x-input-error :messages="$errors->get('medical_conditions')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Create Student') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
