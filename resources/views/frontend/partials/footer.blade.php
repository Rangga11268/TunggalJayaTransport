<footer class="bg-gradient-to-r from-gray-900 to-blue-900 text-white">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="mb-6 md:mb-0">
                <a href="{{ route('frontend.home') }}" class="flex items-center mb-4 space-x-3">
                    <img src="{{ asset('img/logoNoBg.png') }}" alt="Tunggal Jaya Transport Logo" class="h-12 w-auto">
                    <span class="self-center text-2xl font-bold whitespace-nowrap">Tunggal Jaya Transport</span>
                </a>
                <p class="text-gray-300 mb-4">
                    Providing reliable and comfortable transportation services since 1990.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-white bg-gray-800 p-2 rounded-full transition duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white bg-gray-800 p-2 rounded-full transition duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white bg-gray-800 p-2 rounded-full transition duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white bg-gray-800 p-2 rounded-full transition duration-300">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-bold mb-4 border-b border-blue-500 pb-2">Quick Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('frontend.about') }}" class="text-gray-300 hover:text-white hover:underline transition duration-300 flex items-center">
                            <i class="fas fa-chevron-right mr-2 text-blue-400"></i> About Us
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.routes.index') }}" class="text-gray-300 hover:text-white hover:underline transition duration-300 flex items-center">
                            <i class="fas fa-chevron-right mr-2 text-blue-400"></i> Routes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.fleet.index') }}" class="text-gray-300 hover:text-white hover:underline transition duration-300 flex items-center">
                            <i class="fas fa-chevron-right mr-2 text-blue-400"></i> Our Fleet
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.news.index') }}" class="text-gray-300 hover:text-white hover:underline transition duration-300 flex items-center">
                            <i class="fas fa-chevron-right mr-2 text-blue-400"></i> News & Updates
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.contact') }}" class="text-gray-300 hover:text-white hover:underline transition duration-300 flex items-center">
                            <i class="fas fa-chevron-right mr-2 text-blue-400"></i> Contact Us
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h3 class="text-lg font-bold mb-4 border-b border-blue-500 pb-2">Services</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white hover:underline transition duration-300 flex items-center">
                            <i class="fas fa-bus mr-2 text-blue-400"></i> City Transport
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white hover:underline transition duration-300 flex items-center">
                            <i class="fas fa-route mr-2 text-blue-400"></i> Intercity Travel
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white hover:underline transition duration-300 flex items-center">
                            <i class="fas fa-plane mr-2 text-blue-400"></i> Airport Shuttle
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white hover:underline transition duration-300 flex items-center">
                            <i class="fas fa-map-marked-alt mr-2 text-blue-400"></i> Tour Packages
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-300 hover:text-white hover:underline transition duration-300 flex items-center">
                            <i class="fas fa-building mr-2 text-blue-400"></i> Corporate Travel
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-bold mb-4 border-b border-blue-500 pb-2">Contact Us</h3>
                <ul class="space-y-3 text-gray-300">
                    <li class="flex items-start hover:text-white transition duration-300">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-blue-400"></i>
                        <span>123 Transport Street, City, Country</span>
                    </li>
                    <li class="flex items-center hover:text-white transition duration-300">
                        <i class="fas fa-phone-alt mr-3 text-blue-400"></i>
                        <span>+1 (555) 123-4567</span>
                    </li>
                    <li class="flex items-center hover:text-white transition duration-300">
                        <i class="fas fa-envelope mr-3 text-blue-400"></i>
                        <span>info@tunggaljayatransport.com</span>
                    </li>
                    <li class="flex items-center hover:text-white transition duration-300">
                        <i class="fas fa-clock mr-3 text-blue-400"></i>
                        <span>Mon-Fri: 8AM - 8PM</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="my-6 border-gray-700 sm:mx-auto" />

        <div class="sm:flex sm:items-center sm:justify-between">
            <span class="text-sm text-gray-300 sm:text-center">
                © {{ date('Y') }} <a href="{{ route('frontend.home') }}" class="hover:underline hover:text-white">Tunggal Jaya Transport</a>. 
                All Rights Reserved.
            </span>
            <div class="flex mt-4 space-x-6 sm:justify-center sm:mt-0">
                <a href="#" class="text-gray-300 hover:text-white hover:underline transition duration-300">
                    <span class="sr-only">Privacy Policy</span>
                    Privacy
                </a>
                <a href="#" class="text-gray-300 hover:text-white hover:underline transition duration-300">
                    <span class="sr-only">Terms & Conditions</span>
                    Terms
                </a>
                <a href="#" class="text-gray-300 hover:text-white hover:underline transition duration-300">
                    <span class="sr-only">Cookie Policy</span>
                    Cookies
                </a>
            </div>
        </div>
    </div>
</footer>