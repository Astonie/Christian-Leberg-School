<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Teacher') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('teachers.update', $teacher) }}">
                        @csrf
                        @method('PUT')

                        <!-- Splitting name to fill first/last is tricky if only one name field exists in User.
                             Assuming we split by space or just put raw values if we stored them separately.
                             Standard User model has 'name'. Our controller request expects first_name/last_name.
                             Let's attempt to split or just let user edit.
                             Actually, controller logic: $teacher->user->update(['name' => $request->first_name . ' ' . $request->last_name]);
                             So we should likely split the current name to populate the form fields roughly.
                        -->
                        @php
                            $nameParts = explode(' ', $teacher->user->name, 2);
                            $firstName = $nameParts[0] ?? '';
                            $lastName = $nameParts[1] ?? '';
                        @endphp

                        <h3 class="font-semibold text-lg border-b pb-2 mb-4">User Account & Basic Info</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $firstName)" required autofocus />
                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $lastName)" required />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $teacher->user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="font-semibold text-lg border-b pb-2 mb-4">Employment Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="employee_number" :value="__('Employee Number')" />
                                <x-text-input id="employee_number" class="block mt-1 w-full" type="text" name="employee_number" :value="old('employee_number', $teacher->employee_number)" required />
                                <x-input-error :messages="$errors->get('employee_number')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="hire_date" :value="__('Hire Date')" />
                                <x-text-input id="hire_date" class="block mt-1 w-full" type="date" name="hire_date" :value="old('hire_date', $teacher->hire_date?->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('hire_date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="employment_type" :value="__('Employment Type')" />
                                <select id="employment_type" name="employment_type" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                    <option value="full-time" {{ old('employment_type', $teacher->employment_type) == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part-time" {{ old('employment_type', $teacher->employment_type) == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ old('employment_type', $teacher->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                </select>
                                <x-input-error :messages="$errors->get('employment_type')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="phone_number" :value="__('Phone Number')" />
                                <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $teacher->phone_number)" required />
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>
                        </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="qualification" :value="__('Qualification')" />
                                <x-text-input id="qualification" class="block mt-1 w-full" type="text" name="qualification" :value="old('qualification', $teacher->qualification)" />
                                <x-input-error :messages="$errors->get('qualification')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="specialization" :value="__('Specialization')" />
                                <x-text-input id="specialization" class="block mt-1 w-full" type="text" name="specialization" :value="old('specialization', $teacher->specialization)" />
                                <x-input-error :messages="$errors->get('specialization')" class="mt-2" />
                            </div>
                         </div>
                        
                        <div class="mb-6">
                            <x-input-label for="address" :value="__('Address')" />
                            <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" rows="3">{{ old('address', $teacher->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('teachers.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Teacher') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
