<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - JOLITS</title>
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
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-dark mb-2">JOLITS</h1>
                <p class="text-gray-600">Create your account</p>
            </div>

            <!-- Registration Form -->
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

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-dark font-semibold mb-2">Full Name</label>
                        <input type="text" name="name" required 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none"
                               value="{{ old('name') }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-dark font-semibold mb-2">Email Address</label>
                        <input type="email" name="email" required 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none"
                               value="{{ old('email') }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-dark font-semibold mb-2">Phone Number</label>
                        <input type="tel" name="phone" required 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none"
                               value="{{ old('phone') }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-dark font-semibold mb-2">Password</label>
                        <input type="password" name="password" required 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none">
                    </div>

                    <div class="mb-6">
                        <label class="block text-dark font-semibold mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" required 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none">
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                        Create Account
                    </button>
                </form>

                <div class="my-5 flex items-center">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-3 text-sm text-gray-500">or</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <a href="{{ route('auth.google') }}"
                   class="w-full inline-flex items-center justify-center gap-3 border-2 border-gray-300 py-3 rounded-lg font-semibold text-dark hover:bg-gray-50 transition">
                    <span>Continue with Google</span>
                </a>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Sign In</a>
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
