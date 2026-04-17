@extends('layouts.admin')

@section('title', 'Add Menu Item')

@section('page-title', 'Add New Menu Item')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block text-gray-900 font-bold mb-2">Item Name *</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white"
                       value="{{ old('name') }}"
                       placeholder="e.g., Chicken Burger">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-900 font-bold mb-2">Description *</label>
                <textarea name="description" rows="4" required
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white"
                          placeholder="Describe your menu item...">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-900 font-bold mb-2">Category *</label>
                    <select name="category_id" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white">
                        <option value="">Select Category</option>
                        @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-900 font-bold mb-2">Price ($) *</label>
                    <input type="number" name="price" step="0.01" min="0" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white"
                           value="{{ old('price') }}"
                           placeholder="0.00">
                    @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-900 font-bold mb-2">Item Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white">
                <p class="text-sm text-gray-600 mt-1">Recommended size: 800x600px. Max 2MB. Formats: JPG, PNG, WEBP</p>
                @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_available" value="1"
                           {{ old('is_available', true) ? 'checked' : '' }}
                           class="w-5 h-5 text-primary border-2 border-gray-300 rounded">
                    <span class="ml-3 text-gray-900 font-bold">Available for ordering</span>
                </label>
                <p class="text-sm text-gray-600 mt-1 ml-8">Customers can only order items that are marked as available</p>
            </div>
            <div class="mb-6">
                <label class="block text-gray-900 font-bold mb-2">Preparation Time (mins) *</label>
                <input type="number" name="preparation_time" min="1" required
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white"
                    value="{{ old('preparation_time', 10) }}">
                @error('preparation_time')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div class="flex gap-4">
                <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    Add Menu Item
                </button>
                <a href="{{ route('admin.menu.index') }}" class="bg-dark text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
