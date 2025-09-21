<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Test Sidebar Dropdowns') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Sidebar Dropdown Test</h3>
                    <p>This page is for testing the sidebar dropdown functionality.</p>
                    <p>Check the sidebar to see if the dropdown menus are working correctly.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>