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
                <p class="text-gray-600"><strong>Available Seats:</strong> {{ $schedule->getAvailableSeatsCount() }} / {{ $schedule->bus->capacity }}</p>
                <p class="text-gray-600"><strong>Price:</strong> Rp. {{ number_format($schedule->price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Passenger Information</h2>
        <form method="POST" action="{{ route('frontend.booking.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="passenger_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="passenger_name" name="passenger_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="passenger_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="passenger_email" name="passenger_email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="passenger_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" id="passenger_phone" name="passenger_phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="number_of_seats" class="block text-sm font-medium text-gray-700">Number of Seats</label>
                    <select id="number_of_seats" name="number_of_seats" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="terms" name="terms" required class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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