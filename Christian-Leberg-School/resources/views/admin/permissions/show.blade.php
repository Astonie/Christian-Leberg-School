<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Permission: {{ $permission->name }}</h2>
            <div class="space-x-2">
                <a href="{{ route('admin.permissions.edit', $permission) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg transition-colors">
                    Edit Permission
                </a>
                <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors">
                    Back to Permissions
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Permission Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Permission Details</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $permission->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $permission->slug }}
                                </span>
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $permission->description ?? 'No description' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Roles Count</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $permission->roles->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Roles -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Roles with this Permission ({{ $permission->roles->count() }})</h3>
                    @if($permission->roles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($permission->roles as $role)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $role->name }}</h4>
                                            <span class="mt-1 inline-block px-2 py-1 text-xs rounded bg-blue-100 text-blue-600">
                                                {{ $role->slug }}
                                            </span>
                                        </div>
                                        <a href="{{ route('admin.roles.show', $role) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                            View
                                        </a>
                                    </div>
                                    @if($role->description)
                                        <p class="mt-2 text-xs text-gray-500">{{ $role->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">This permission is not assigned to any roles.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
