@extends('layouts.app')

@section('title', 'My Profile - JOLITS')

@section('content')
<div class="bg-dark text-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold">My Profile</h1>
        <p class="mt-2">Update your account information</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto bg-gray-800 border-2 border-gray-700 rounded-lg p-8">
        @if(session('success'))
            <div class="mb-4 bg-green-900 border-2 border-green-700 text-green-200 p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-900 border-2 border-red-700 text-red-200 p-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-white font-semibold mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-3 bg-gray-700 border-2 border-gray-600 text-white rounded-lg focus:border-gray-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-white font-semibold mb-2">Email</label>
                <input type="email" value="{{ $user->email }}" disabled
                       class="w-full px-4 py-3 bg-gray-900 border-2 border-gray-700 text-gray-400 rounded-lg">
                <p class="text-xs text-gray-400 mt-1">Email changes are disabled for security.</p>
            </div>

            <div class="mb-4">
                <label class="block text-white font-semibold mb-2">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full px-4 py-3 bg-gray-700 border-2 border-gray-600 text-white rounded-lg focus:border-gray-500 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-white font-semibold mb-2">Address</label>
                <textarea name="address" rows="4"
                          class="w-full px-4 py-3 bg-gray-700 border-2 border-gray-600 text-white rounded-lg focus:border-gray-500 focus:outline-none">{{ old('address', $user->address) }}</textarea>
            </div>

            <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                Save Profile
            </button>
        </form>
    </div>
</div>
@endsection
