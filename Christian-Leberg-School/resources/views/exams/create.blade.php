<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Exam</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('exams.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Exam Name')" />
                        <x-text-input id="name" name="name" class="block mt-1 w-full" value="{{ old('name') }}" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="academic_year_id" :value="__('Academic Year')" />
                        <select name="academic_year_id" id="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach($years as $y)
                                <option value="{{ $y->id }}">{{ $y->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="term" :value="__('Term')" />
                        <x-text-input id="term" name="term" class="block mt-1 w-full" value="{{ old('term') }}" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" name="start_date" type="date" class="block mt-1 w-full" required />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" name="end_date" type="date" class="block mt-1 w-full" required />
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
