<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JOLITS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        dark: '#1f2937',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-dark mb-2">JOLITS</h1>
                <p class="text-gray-600">Sign in to your account</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white border-2 border-gray-200 rounded-lg p-8">
                @if($errors->any())
                <div class="bg-red-50 border-2 border-red-200 text-red-600 rounded-lg p-4 mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-dark font-semibold mb-2">Email Address</label>
                        <input type="email" name="email" required 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none"
                               value="{{ old('email') }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-dark font-semibold mb-2">Password</label>
                        <input type="password" name="password" required 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none">
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-primary">
                            <span class="ml-2 text-dark">Remember me</span>
                        </label>
                        <a href="#" class="text-primary hover:underline">Forgot Password?</a>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                        Sign In
                    </button>
                </form>

                <div class="my-5 flex items-center">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-3 text-sm text-gray-500">or</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <a href="{{ route('auth.google') }}"
                   class="w-full inline-flex items-center justify-center gap-3 border-2 border-gray-300 py-3 rounded-lg font-semibold text-dark hover:bg-gray-50 transition">
                    <span>Sign in with Google</span>
                </a>
            </div>

            <!-- Register Link -->
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Sign Up</a>
                </p>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-4">
                <a href="{{ route('customer.home') }}" class="text-dark hover:text-primary">
                    ← Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
