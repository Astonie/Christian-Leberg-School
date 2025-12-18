<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Subjects for {{ $teacher->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Assigned Subjects -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg border-b pb-2 mb-4">Assigned Subjects</h3>
                        
                        @if($teacher->subjects->count() > 0)
                            <table class="min-w-full mt-4">
                                <thead>
                                    <tr>
                                        <th class="text-left py-2">Code</th>
                                        <th class="text-left py-2">Name</th>
                                        <th class="text-left py-2">Primary?</th>
                                        <th class="text-right py-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->subjects as $subject)
                                        <tr class="border-t">
                                            <td class="py-3">{{ $subject->code }}</td>
                                            <td class="py-3">{{ $subject->name }}</td>
                                            <td class="py-3">
                                                @if($subject->pivot->is_primary)
                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Yes</span>
                                                @else
                                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">No</span>
                                                @endif
                                            </td>
                                            <td class="text-right py-3">
                                                <form action="{{ route('teachers.subjects.destroy', [$teacher, $subject]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Remove subject?')">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-gray-500 italic mt-4">No subjects assigned yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Assign New Subject -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-fit">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg border-b pb-2 mb-4">Assign Subject</h3>
                        
                        <form action="{{ route('teachers.subjects.store', $teacher) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <x-input-label for="subject_id" :value="__('Select Subject')" />
                                <select id="subject_id" name="subject_id" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                    <option value="">-- Select Subject --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <label for="is_primary" class="inline-flex items-center">
                                    <input id="is_primary" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="is_primary" value="1">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Is Primary Subject?') }}</span>
                                </label>
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button>
                                    {{ __('Assign') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            
            <div class="mt-6">
                <a href="{{ route('teachers.show', $teacher) }}" class="text-blue-600 hover:text-blue-900 font-medium">&larr; Back to Teacher Profile</a>
            </div>
        </div>
    </div>
</x-app-layout>
