@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">Booking Details</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Schedule Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium mb-2">Route Details</h3>
                <p class="text-gray-600"><strong>Origin:</strong> {{ $schedule->route->origin }}</p>
                <p class="text-gray-600"><strong>Destination:</strong> {{ $schedule->route->destination }}</p>
                <p class="text-gray-600"><strong>Distance:</strong> {{ $schedule->route->distance }} km</p>
                <p class="text-gray-600"><strong>Duration:</strong> {{ $schedule->route->duration }} hours</p>
            </div>
            <div>
                <h3 class="text-lg font-medium mb-2">Schedule Details</h3>
                <p class="text-gray-600"><strong>Departure:</strong> {{ $schedule->departure_time->format('d M Y H:i') }}</p>
                <p class="text-gray-600"><strong>Arrival:</strong> {{ $schedule->arrival_time->format('d M Y H:i') }}</p>
                <p class="text-gray-600"><strong>Bus Type:</strong> {{ $schedule->bus->bus_type ?? 'Standard' }}</p>
                <p class="text-gray-600"><strong>Price:</strong> Rp. {{ number_format($schedule->price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Passenger Information</h2>
        <form class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="id_number" class="block text-sm font-medium text-gray-700">ID Number</label>
                    <input type="text" id="id_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>
            <div>
                <label for="seats" class="block text-sm font-medium text-gray-700">Number of Seats</label>
                <select id="seats" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="terms" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    I agree to the <a href="#" class="text-blue-500 hover:underline">Terms and Conditions</a>
                </label>
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Proceed to Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection