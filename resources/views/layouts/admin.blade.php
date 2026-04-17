<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - JOLITS Admin</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind -->
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

    <style>
        html { font-size: 16px; }
        body {
            background-color: #111827;
        }
        .nav-active {
            @apply bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-lg;
        }
        .nav-link {
            @apply flex items-center gap-3 px-4 py-2 rounded-lg transition duration-200 text-gray-300 hover:text-white hover:bg-gray-800;
        }
    </style>
</head>
<body class="min-h-screen text-[15px] md:text-base text-gray-100">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-black to-gray-900 text-white p-5 hidden md:flex md:flex-col border-r border-gray-800 fixed h-screen left-0 top-0 overflow-y-auto">
        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 text-xl font-bold mb-8 hover:text-cyan-400 transition">
            <span class="text-2xl">⚙️</span>
            <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">JOLITS</span>
        </a>

        <!-- Navigation -->
        <nav class="flex flex-col gap-0">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
                <i class="fas fa-chart-line w-5"></i>
                <span>Dashboard</span>
                <span class="ml-auto text-xs bg-cyan-600 px-2 py-1 rounded-full">Live</span>
            </a>

            <a href="{{ route('admin.menu.index') }}" class="nav-link {{ request()->routeIs('admin.menu.*') ? 'nav-active' : '' }}">
                <i class="fas fa-utensils w-5"></i>
                <span>Menu Items</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'nav-active' : '' }}">
                <i class="fas fa-shopping-bag w-5"></i>
                <span>Orders</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'nav-active' : '' }}">
                <i class="fas fa-folder-open w-5"></i>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'nav-active' : '' }}">
                <i class="fas fa-users w-5"></i>
                <span>Users</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'nav-active' : '' }}">
                <i class="fas fa-chart-bar w-5"></i>
                <span>Sales Reports</span>
            </a>

            <a href="{{ route('customer.home') }}" class="nav-link" target="_blank">
                <i class="fas fa-external-link-alt w-5"></i>
                <span>View Website</span>
            </a>
        </nav>

        <!-- User Info & Logout -->
        <div class="border-t border-gray-700 pt-4 mt-auto">
            <div class="bg-gray-800 rounded-lg p-3 mb-4">
                <p class="text-xs text-gray-400 mb-2">Logged in as</p>
                <p class="font-semibold text-white flex items-center gap-2 text-sm">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    {{ auth()->user()->name ?? 'Admin' }}
                </p>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white py-2.5 rounded-lg font-semibold transition transform hover:scale-105">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 p-6 md:p-8 overflow-auto">
        <!-- Page Header -->
        <div class="mb-8 pb-6 border-b border-gray-700">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-1">@yield('title')</h1>
                    <p class="text-gray-400">Manage your e-commerce platform efficiently</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 mb-1">Today</p>
                    <p class="text-lg font-semibold text-cyan-400">{{ now()->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-900 to-green-800 border-l-4 border-green-500 text-green-200 p-4 rounded-lg flex items-center gap-3">
                <i class="fas fa-check-circle text-xl flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-900 to-red-800 border-l-4 border-red-500 text-red-200 p-4 rounded-lg flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-xl flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>
</div>
</body>
</html>
