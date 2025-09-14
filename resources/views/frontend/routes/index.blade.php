@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">Our Routes</h1>
    
    <!-- Interactive Map -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Route Map</h2>
        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-96 flex items-center justify-center">
            <span class="text-2xl text-gray-500">Interactive Map</span>
        </div>
    </div>

    <!-- Route List -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">All Routes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($routes as $route)
            <div class="border rounded-lg p-4">
                <h3 class="text-lg font-bold">{{ $route->name ?? $route->origin . ' - ' . $route->destination }}</h3>
                <p class="text-gray-600">Distance: {{ $route->distance }} km</p>
                <p class="text-gray-600">Duration: {{ $route->duration }} hours</p>
                <a href="{{ route('frontend.routes.show', $route->id) }}" class="text-blue-500 hover:underline mt-2 inline-block">View Schedule</a>
            </div>
            @empty
            <div class="col-span-3">
                <p class="text-gray-600 text-center">No routes available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection