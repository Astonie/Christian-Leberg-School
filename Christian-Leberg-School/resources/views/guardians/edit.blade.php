<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Guardian') }}: {{ $guardian->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('guardians.update', $guardian) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- User Details -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Account Details</h3>
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $guardian->user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $guardian->user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Guardian Details -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Profile Details</h3>
                                <div>
                                    <label for="relationship" class="block text-sm font-medium text-gray-700">Relationship to Student</label>
                                    <select name="relationship" id="relationship" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Select Relationship</option>
                                        @foreach(['Father', 'Mother', 'Grandparent', 'Uncle', 'Aunt', 'Sibling', 'Other'] as $rel)
                                            <option value="{{ $rel }}" {{ old('relationship', $guardian->relationship) == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                        @endforeach
                                    </select>
                                    @error('relationship') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $guardian->phone_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="occupation" class="block text-sm font-medium text-gray-700">Occupation</label>
                                    <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $guardian->occupation) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $guardian->address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Link Students -->
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Assign Wards (Students)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-h-60 overflow-y-auto p-4 border rounded-md">
                                @foreach($students as $student)
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            {{ $guardian->students->contains($student->id) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700">{{ $student->user->name }} ({{ $student->admission_number }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end pt-6">
                            <a href="{{ route('guardians.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 self-center">Cancel</a>
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Update Guardian</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
