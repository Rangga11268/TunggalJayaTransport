<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sidebar Test') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Wide Content Test</h3>
                    
                    <!-- Very wide table to test sidebar behavior -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    @for ($i = 1; $i <= 20; $i++)
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Column {{ $i }}
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @for ($row = 1; $row <= 10; $row++)
                                    <tr>
                                        @for ($col = 1; $col <= 20; $col++)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                Row {{ $row }}, Col {{ $col }}
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Wide image to test sidebar behavior -->
                    <div class="mt-8">
                        <h4 class="text-md font-bold mb-2">Wide Image Test</h4>
                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-64 flex items-center justify-center">
                            <span class="text-gray-500">Placeholder for wide content</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>