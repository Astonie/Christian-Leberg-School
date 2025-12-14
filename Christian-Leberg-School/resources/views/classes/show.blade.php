<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Streams for') }} {{ $schoolClass->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Streams List -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Existing Streams</h3>
                            
                            @if($schoolClass->streams->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stream Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class Teacher</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($schoolClass->streams as $stream)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stream->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $stream->capacity ?? 'Unlimited' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <form method="POST" action="{{ route('streams.update', $stream) }}" class="flex items-center">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="name" value="{{ $stream->name }}">
                                                        <input type="hidden" name="capacity" value="{{ $stream->capacity }}">
                                                        
                                                        <select name="class_teacher_id" class="text-xs border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="this.form.submit()">
                                                            <option value="">-- Select --</option>
                                                            @foreach($teachers as $teacher)
                                                                <option value="{{ $teacher->id }}" {{ ($stream->class_teacher?->id == $teacher->id) ? 'selected' : '' }}>
                                                                    {{ $teacher->user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <!-- Edit Modal Trigger (simplifying to inline edit for now is complex, just delete or edit separately. We'll stick to delete for brevity unless requested) -->
                                                    <!-- Actually, let's keep it simple: Delete only for now, or edit name via a separate small form if crucial. I'll add Delete. -->
                                                    <form action="{{ route('streams.destroy', $stream) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure? This will delete the stream.')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-gray-500 italic">No streams created for this class yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Add Stream Form -->
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Stream</h3>
                            <form method="POST" action="{{ route('streams.store') }}">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $schoolClass->id }}">

                                <div class="mb-4">
                                    <x-input-label for="name" :value="__('Stream Name (e.g. A, Blue, North)')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="capacity" :value="__('Capacity (Optional)')" />
                                    <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity" :value="old('capacity')" />
                                    <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end">
                                    <x-primary-button>
                                        {{ __('Add Stream') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
