<footer class="bg-gradient-to-r from-blue-900 to-indigo-900 text-white rounded-lg shadow-lg m-4">
    <div class="w-full max-w-screen-xl mx-auto p-6 md:py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="mb-6 md:mb-0">
                <a href="{{ route('frontend.home') }}" class="flex items-center mb-4 space-x-3">
                    <x-application-logo class="h-10" />
                    <span class="self-center text-2xl font-bold whitespace-nowrap">Tunggal Jaya Transport</span>
                </a>
                <p class="text-gray-300 mb-4">
                    Providing reliable and comfortable transportation services since 1990.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-white">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('frontend.about') }}" class="text-gray-300 hover:text-white hover:underline">About Us</a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.routes.index') }}" class="text-gray-300 hover:text-white hover:underline">Routes</a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.fleet.index') }}" class="text-gray-300 hover:text-white hover:underline">Our Fleet</a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.news.index') }}" class="text-gray-300 hover:text-white hover:underline">News & Updates</a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.contact') }}" class="text-gray-300 hover:text-white hover:underline">Contact Us</a>
                    </li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h3 class="text-lg font-bold mb-4">Services</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white hover:underline">City Transport</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white hover:underline">Intercity Travel</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white hover:underline">Airport Shuttle</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white hover:underline">Tour Packages</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white hover:underline">Corporate Travel</a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-bold mb-4">Contact Us</h3>
                <ul class="space-y-3 text-gray-300">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                        <span>123 Transport Street, City, Country</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone-alt mr-3"></i>
                        <span>+1 (555) 123-4567</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-3"></i>
                        <span>info@tunggaljayatransport.com</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-clock mr-3"></i>
                        <span>Mon-Fri: 8AM - 8PM</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="my-6 border-gray-700 sm:mx-auto" />

        <div class="sm:flex sm:items-center sm:justify-between">
            <span class="text-sm text-gray-300 sm:text-center">
                © {{ date('Y') }} <a href="{{ route('frontend.home') }}" class="hover:underline">Tunggal Jaya Transport</a>. 
                All Rights Reserved.
            </span>
            <div class="flex mt-4 space-x-6 sm:justify-center sm:mt-0">
                <a href="#" class="text-gray-300 hover:text-white">
                    <span class="sr-only">Privacy Policy</span>
                    Privacy
                </a>
                <a href="#" class="text-gray-300 hover:text-white">
                    <span class="sr-only">Terms & Conditions</span>
                    Terms
                </a>
                <a href="#" class="text-gray-300 hover:text-white">
                    <span class="sr-only">Cookie Policy</span>
                    Cookies
                </a>
            </div>
        </div>
    </div>
</footer>