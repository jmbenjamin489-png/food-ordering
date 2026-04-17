@extends('layouts.admin')

@section('title', 'Edit Menu Item')
@section('page-title', 'Edit Menu Item')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">

        <form action="{{ route('admin.menu.update', $menu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-6">
                <label class="block text-gray-900 font-bold mb-2">Item Name *</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white"
                       value="{{ old('name', $menu->name) }}">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-gray-900 font-bold mb-2">Description *</label>
                <textarea name="description" rows="4" required
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white">{{ old('description', $menu->description) }}</textarea>
                @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category & Price -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-900 font-bold mb-2">Category *</label>
                    <select name="category_id" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
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
                           value="{{ old('price', $menu->price) }}">
                    @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Preparation Time -->
            <div class="mb-6">
                <label class="block text-gray-900 font-bold mb-2">Preparation Time (minutes) *</label>
                <input type="number" name="preparation_time" min="1" required
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white"
                       value="{{ old('preparation_time', $menu->preparation_time) }}">
                @error('preparation_time')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div class="mb-6">
                <label class="block text-gray-900 font-bold mb-2">Item Image</label>

                @if($menu->image)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-48 h-48 object-cover rounded-lg border-2 border-gray-200">
                </div>
                @endif

                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none text-gray-900 bg-white">
                <p class="text-sm text-gray-600 mt-1">
                    {{ $menu->image ? 'Upload a new image to replace the current one.' : 'Recommended size: 800x600px. Max 2MB.' }}
                </p>
                @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkboxes -->
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_available" value="1"
                           {{ old('is_available', $menu->is_available) ? 'checked' : '' }}
                           class="w-5 h-5 text-primary border-2 border-gray-300 rounded">
                    <span class="ml-3 text-gray-900 font-bold">Available for ordering</span>
                </label>

                <label class="flex items-center cursor-pointer mt-2">
                    <input type="checkbox" name="is_featured" value="1"
                           {{ old('is_featured', $menu->is_featured) ? 'checked' : '' }}
                           class="w-5 h-5 text-primary border-2 border-gray-300 rounded">
                    <span class="ml-3 text-gray-900 font-bold">Featured Item</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    Update Menu Item
                </button>
                <a href="{{ route('admin.menu.index') }}" class="bg-dark text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Delete Form -->
        <div class="mt-8 pt-8 border-t-2 border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Danger Zone</h3>
            <p class="text-sm text-gray-600 mb-4">Once you delete this menu item, there is no going back. Please be certain.</p>
            <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this menu item? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:opacity-90 transition">
                    Delete Menu Item
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
