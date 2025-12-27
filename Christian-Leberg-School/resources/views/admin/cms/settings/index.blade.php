<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Website Settings
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.cms.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if($settings->count() > 0)
                            @foreach($settings as $group => $groupSettings)
                                <div class="mb-8">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b capitalize">
                                        {{ str_replace('_', ' ', $group) }}
                                    </h3>

                                    <div class="space-y-6">
                                        @foreach($groupSettings as $setting)
                                            <div>
                                                <label for="setting_{{ $setting->key }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ $setting->label ?? ucwords(str_replace('_', ' ', $setting->key)) }}
                                                </label>

                                                @if($setting->description)
                                                    <p class="text-xs text-gray-500 mb-2">{{ $setting->description }}</p>
                                                @endif

                                                @switch($setting->type)
                                                    @case('textarea')
                                                        <textarea 
                                                            name="settings[{{ $setting->key }}]" 
                                                            id="setting_{{ $setting->key }}"
                                                            rows="4"
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                        >{{ old("settings.{$setting->key}", $setting->value) }}</textarea>
                                                        @break

                                                    @case('boolean')
                                                        <div class="mt-1">
                                                            <label class="inline-flex items-center">
                                                                <input 
                                                                    type="checkbox" 
                                                                    name="settings[{{ $setting->key }}]" 
                                                                    value="1"
                                                                    {{ old("settings.{$setting->key}", $setting->value) ? 'checked' : '' }}
                                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                                <span class="ml-2 text-sm text-gray-600">Enable</span>
                                                            </label>
                                                        </div>
                                                        @break

                                                    @case('image')
                                                        <div class="space-y-2">
                                                            @if($setting->value)
                                                                <div class="mb-2">
                                                                    <img src="{{ Storage::url($setting->value) }}" alt="{{ $setting->label }}" class="h-20 rounded">
                                                                </div>
                                                            @endif
                                                            <input 
                                                                type="file" 
                                                                name="settings[{{ $setting->key }}]" 
                                                                id="setting_{{ $setting->key }}"
                                                                accept="image/*"
                                                                class="block w-full text-sm text-gray-500
                                                                    file:mr-4 file:py-2 file:px-4
                                                                    file:rounded-md file:border-0
                                                                    file:text-sm file:font-semibold
                                                                    file:bg-blue-50 file:text-blue-700
                                                                    hover:file:bg-blue-100">
                                                        </div>
                                                        @break

                                                    @case('select')
                                                        <select 
                                                            name="settings[{{ $setting->key }}]" 
                                                            id="setting_{{ $setting->key }}"
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                            @if($setting->options)
                                                                @foreach(json_decode($setting->options, true) as $optionValue => $optionLabel)
                                                                    <option value="{{ $optionValue }}" {{ old("settings.{$setting->key}", $setting->value) == $optionValue ? 'selected' : '' }}>
                                                                        {{ $optionLabel }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @break

                                                    @default
                                                        <input 
                                                            type="text" 
                                                            name="settings[{{ $setting->key }}]" 
                                                            id="setting_{{ $setting->key }}"
                                                            value="{{ old("settings.{$setting->key}", $setting->value) }}"
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                @endswitch

                                                @error("settings.{$setting->key}")
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end pt-6 border-t">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                    Save Settings
                                </button>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No settings configured</h3>
                                <p class="mt-1 text-sm text-gray-500">Settings will appear here once they are added to the database.</p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
