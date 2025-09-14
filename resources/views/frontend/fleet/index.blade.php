@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">Our Fleet</h1>
    
    <!-- Bus Gallery -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Bus Gallery</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="border rounded-lg overflow-hidden">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48"></div>
                <div class="p-4">
                    <h3 class="text-lg font-bold">Executive Class</h3>
                    <p class="text-gray-600">Luxury seating with extra legroom</p>
                </div>
            </div>
            <div class="border rounded-lg overflow-hidden">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48"></div>
                <div class="p-4">
                    <h3 class="text-lg font-bold">Business Class</h3>
                    <p class="text-gray-600">Comfortable seating with reclining</p>
                </div>
            </div>
            <div class="border rounded-lg overflow-hidden">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48"></div>
                <div class="p-4">
                    <h3 class="text-lg font-bold">Economy Class</h3>
                    <p class="text-gray-600">Affordable travel with basic comfort</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Facilities -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Facilities</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto mb-2"></div>
                <h3 class="text-lg font-bold">Air Conditioning</h3>
            </div>
            <div class="text-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto mb-2"></div>
                <h3 class="text-lg font-bold">Wi-Fi</h3>
            </div>
            <div class="text-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto mb-2"></div>
                <h3 class="text-lg font-bold">Entertainment</h3>
            </div>
            <div class="text-center">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto mb-2"></div>
                <h3 class="text-lg font-bold">Restroom</h3>
            </div>
        </div>
    </div>
</div>
@endsection