<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Online Food Ordering')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0891b2',
                        dark: '#1f2937',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-950 text-gray-100">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-black text-white shadow-xl border-b border-gray-800 backdrop-blur">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ route('customer.home') }}" class="flex items-center space-x-2 text-2xl font-bold hover:text-cyan-400 transition">
                    <span class="text-3xl">🍽️</span>
                    <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">JOLITS</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 font-semibold">
                    <a href="{{ route('customer.home') }}" class="hover:text-cyan-400 transition duration-200">Home</a>
                    <a href="{{ route('customer.menu') }}" class="hover:text-cyan-400 transition duration-200">Menu</a>
                    @auth
                        <a href="{{ route('customer.orders') }}" class="hover:text-cyan-400 transition duration-200">Orders</a>
                        <a href="{{ route('customer.profile') }}" class="hover:text-cyan-400 transition duration-200">Profile</a>
                    @endauth
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Cart Link -->
                        <a href="{{ route('customer.cart') }}" class="relative group">
                            <span class="text-2xl hover:text-cyan-400 transition duration-200">🛒</span>
                        </a>
                        
                        <!-- Admin Link -->
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hidden md:inline-block bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white px-4 py-2 rounded-lg transition text-sm font-semibold">
                                Admin Panel
                            </a>
                        @endif
                        
                        <!-- Logout -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-red-400 transition duration-200 text-sm font-semibold">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-cyan-400 transition text-sm font-semibold">Login</a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white px-4 py-2 rounded-lg transition text-sm font-semibold">
                            Sign Up
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen bg-gray-950">
        @if(session('success'))
            <div class="container mx-auto px-4 pt-4">
                <div class="bg-green-900 border-2 border-green-700 text-green-200 p-4 rounded-lg flex items-center gap-3">
                    <span class="text-2xl">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mx-auto px-4 pt-4">
                <div class="bg-red-900 border-2 border-red-700 text-red-200 p-4 rounded-lg flex items-center gap-3">
                    <span class="text-2xl">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-black text-white mt-16 border-t border-gray-800">
        <div class="container mx-auto px-4 py-12">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-3xl">🍽️</span>
                        <h3 class="text-xl font-bold">JOLITS</h3>
                    </div>
                    <p class="text-gray-400 text-sm">Your favorite online food ordering platform delivering quality meals to your doorstep.</p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold mb-4 text-cyan-400">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('customer.home') }}" class="hover:text-cyan-400 transition">Home</a></li>
                        <li><a href="{{ route('customer.menu') }}" class="hover:text-cyan-400 transition">Menu</a></li>
                        @auth
                            <li><a href="{{ route('customer.orders') }}" class="hover:text-cyan-400 transition">My Orders</a></li>
                        @endauth
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="font-bold mb-4 text-cyan-400">Support</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-cyan-400 transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Contact Us</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h4 class="font-bold mb-4 text-cyan-400">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>📧 support@foodhub.com</li>
                        <li>📞 +1 (555) 123-4567</li>
                        <li>📍 Tagum City, Philippines</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; 2026 FoodHub. All rights reserved. | Developed with ❤️ for quality dining</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
