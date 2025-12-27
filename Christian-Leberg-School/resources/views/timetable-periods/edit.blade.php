<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit Timetable Period</h2>
                <p class="text-sm text-gray-600 mt-2">Update period: {{ $timetablePeriod->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="container-mobile section-spacing">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
            <form action="{{ route('timetable-periods.update', $timetablePeriod) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Period Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Period Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $timetablePeriod->name) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="e.g., Period 1, Break, Lunch"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">
                            Order <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="order" 
                               name="order" 
                               value="{{ old('order', $timetablePeriod->order) }}"
                               min="1"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('order') border-red-500 @enderror"
                               required>
                        @error('order')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="start_time" 
                               name="start_time" 
                               value="{{ old('start_time', $timetablePeriod->start_time->format('H:i')) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_time') border-red-500 @enderror"
                               required>
                        @error('start_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="end_time" 
                               name="end_time" 
                               value="{{ old('end_time', $timetablePeriod->end_time->format('H:i')) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_time') border-red-500 @enderror"
                               required>
                        @error('end_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Break -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_break" 
                               name="is_break" 
                               value="1"
                               {{ old('is_break', $timetablePeriod->is_break) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_break" class="ml-2 text-sm font-medium text-gray-700">
                            This is a break period (not for teaching)
                        </label>
                    </div>

                    <!-- Is Active -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $timetablePeriod->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                            Active
                        </label>
                    </div>
                </div>

                <!-- Usage Warning -->
                @if($timetablePeriod->timetableEntries()->count() > 0)
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800">Warning</p>
                                <p class="text-sm text-yellow-700 mt-1">
                                    This period is currently used in {{ $timetablePeriod->timetableEntries()->count() }} timetable entries. 
                                    Changing times may affect existing schedules.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('timetable-periods.index') }}" 
                       class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md transition-all">
                        Update Period
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
