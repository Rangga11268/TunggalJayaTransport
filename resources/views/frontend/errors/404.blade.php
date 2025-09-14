@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
            <h2 class="text-3xl font-bold text-gray-600 mb-6">Page Not Found</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Sorry, the page you are looking for could not be found. It might have been removed, had its name changed, or is temporarily unavailable.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('frontend.home') }}" class="px-6 py-3 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600">
                    Go Home
                </a>
                <a href="{{ route('frontend.contact') }}" class="px-6 py-3 bg-gray-200 text-gray-800 font-medium rounded-md hover:bg-gray-300">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection