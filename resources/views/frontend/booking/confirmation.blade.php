@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-6">Booking Confirmation</h1>
    
    <!-- Booking Summary -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Booking Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium mb-2">Route Information</h3>
                <p><strong>Route:</strong> Jakarta - Bandung</p>
                <p><strong>Date:</strong> September 15, 2025</p>
                <p><strong>Departure:</strong> 08:00</p>
                <p><strong>Arrival:</strong> 12:00</p>
                <p><strong>Bus Type:</strong> Executive</p>
            </div>
            <div>
                <h3 class="text-lg font-medium mb-2">Passenger Information</h3>
                <p><strong>Name:</strong> John Doe</p>
                <p><strong>Email:</strong> john.doe@example.com</p>
                <p><strong>Phone:</strong> +62 812 3456 7890</p>
                <p><strong>Seat Number:</strong> A12</p>
            </div>
        </div>
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex justify-between">
                <span class="text-lg font-bold">Total Price:</span>
                <span class="text-lg font-bold">Rp. 150,000</span>
            </div>
        </div>
    </div>

    <!-- Payment Options -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Payment Method</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border rounded-lg p-4 cursor-pointer hover:border-blue-500">
                <div class="flex items-center">
                    <input type="radio" id="credit-card" name="payment" class="h-4 w-4 text-blue-600">
                    <label for="credit-card" class="ml-2 block text-sm font-medium text-gray-700">Credit/Debit Card</label>
                </div>
            </div>
            <div class="border rounded-lg p-4 cursor-pointer hover:border-blue-500">
                <div class="flex items-center">
                    <input type="radio" id="bank-transfer" name="payment" class="h-4 w-4 text-blue-600">
                    <label for="bank-transfer" class="ml-2 block text-sm font-medium text-gray-700">Bank Transfer</label>
                </div>
            </div>
            <div class="border rounded-lg p-4 cursor-pointer hover:border-blue-500">
                <div class="flex items-center">
                    <input type="radio" id="e-wallet" name="payment" class="h-4 w-4 text-blue-600">
                    <label for="e-wallet" class="ml-2 block text-sm font-medium text-gray-700">E-Wallet</label>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <button class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded">
                Proceed to Payment
            </button>
        </div>
    </div>

    <!-- Seat Selection -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Select Your Seat</h2>
        <div class="flex flex-col items-center">
            <div class="mb-4">
                <div class="bg-gray-800 text-white px-4 py-2 rounded-t-lg inline-block">Driver</div>
            </div>
            <div class="grid grid-cols-4 gap-2">
                @for ($i = 1; $i <= 40; $i++)
                    <div class="bg-green-500 text-white w-12 h-12 flex items-center justify-center rounded cursor-pointer hover:bg-green-600">
                        {{ $i }}
                    </div>
                @endfor
            </div>
            <div class="mt-6 flex space-x-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                    <span class="text-sm">Available</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                    <span class="text-sm">Occupied</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                    <span class="text-sm">Selected</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection