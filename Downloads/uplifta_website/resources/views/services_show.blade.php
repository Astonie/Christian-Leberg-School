@extends('app')
@section('content')
<div class="container mx-auto py-12 max-w-2xl">
    <div class="bg-white p-8 rounded shadow">
        <h1 class="text-3xl font-bold mb-4">{{ $service->title }}</h1>
        <div class="mb-4"><i class="{{ $service->icon }} text-2xl"></i></div>
        <div class="mb-4">{{ $service->description }}</div>
        @if($service->features)
            <ul class="list-disc pl-6 mb-4">
                @foreach(json_decode($service->features, true) as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        @endif
        @if($service->cta_label && $service->cta_link)
            <a href="{{ $service->cta_link }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">{{ $service->cta_label }}</a>
        @endif
        <div class="mt-6">
            <a href="{{ route('services.index') }}" class="text-blue-600 hover:underline">Back to list</a>
        </div>
    </div>
</div>
@endsection
